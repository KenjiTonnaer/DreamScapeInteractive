<?php

namespace App\Http\Controllers;

use App\Models\Player_inventory;
use App\Models\Trade;
use App\Models\TradeItem;
use App\Models\TradeWant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TradesController extends Controller
{
    public function index(): View
    {
        $trades = Trade::query()
            ->with([
                'fromUser',
                'toUser',
                'items.item',
                'wants.item',
            ])
            ->latest()
            ->get();

        $myInventory = Player_inventory::query()
            ->with('item')
            ->where('user_id', Auth::id())
            ->where('quantity', '>', 0)
            ->orderByDesc('quantity')
            ->get();

        return view('user.trades', compact('trades', 'myInventory'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTradePayload($request);

        try {
            DB::transaction(function () use ($validated) {
                $offeredItems = $this->normalizeLineItems($validated['offer_items']);
                $wantedItems = $this->normalizeLineItems($validated['wanted_items']);

                $this->assertInventoryAvailability($offeredItems, Auth::id());

                $trade = Trade::create([
                    'from_user_id' => Auth::id(),
                    'to_user_id' => null,
                    'status' => 'open',
                ]);

                $this->syncTradeItems($trade, $offeredItems);
                $this->syncTradeWants($trade, $wantedItems);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Trade created successfully.');
    }

    public function update(Request $request, Trade $trade): RedirectResponse
    {
        if ((int) $trade->from_user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($trade->status !== 'open' || ! is_null($trade->to_user_id)) {
            return back()->with('error', 'Only open trades can be edited.');
        }

        $validated = $this->validateTradePayload($request);

        try {
            DB::transaction(function () use ($trade, $validated) {
                $offeredItems = $this->normalizeLineItems($validated['offer_items']);
                $wantedItems = $this->normalizeLineItems($validated['wanted_items']);

                $this->assertInventoryAvailability($offeredItems, Auth::id());

                $trade->items()->delete();
                $trade->wants()->delete();

                $this->syncTradeItems($trade, $offeredItems);
                $this->syncTradeWants($trade, $wantedItems);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Trade updated successfully.');
    }

    public function destroy(Trade $trade): RedirectResponse
    {
        if ((int) $trade->from_user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($trade->status !== 'open' || ! is_null($trade->to_user_id)) {
            return back()->with('error', 'Only open trades can be deleted.');
        }

        $trade->delete();

        return back()->with('success', 'Trade deleted successfully.');
    }

    public function offer(Request $request, Trade $trade): RedirectResponse
    {
        $validated = $request->validate([
            'offers' => ['required', 'array', 'min:1'],
            'offers.*.item_id' => ['required', 'integer', 'exists:items,id'],
            'offers.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ((int) $trade->from_user_id === (int) Auth::id()) {
            return back()->with('error', 'You cannot place an offer on your own trade.');
        }

        try {
            DB::transaction(function () use ($trade, $validated) {
                $lockedTrade = Trade::query()
                    ->with('wants')
                    ->whereKey($trade->id)
                    ->whereNull('to_user_id')
                    ->where('status', 'open')
                    ->lockForUpdate()
                    ->first();

                if (! $lockedTrade) {
                    throw new \RuntimeException('Trade is no longer available.');
                }

                $offers = $this->normalizeLineItems($validated['offers']);

                $wantedItemIds = $lockedTrade->wants
                    ->pluck('item_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();

                if (empty($wantedItemIds)) {
                    throw new \RuntimeException('This trade has no requested items configured.');
                }

                foreach ($offers as $offer) {
                    if (! in_array((int) $offer['item_id'], $wantedItemIds, true)) {
                        throw new \RuntimeException('You can only offer items requested in this trade.');
                    }
                }

                $this->assertInventoryAvailability($offers, Auth::id());

                foreach ($offers as $offer) {
                    TradeItem::create([
                        'trade_id' => $lockedTrade->id,
                        'item_id' => $offer['item_id'],
                        'from_user_id' => Auth::id(),
                        'quantity' => $offer['quantity'],
                    ]);

                    Player_inventory::query()
                        ->where('user_id', Auth::id())
                        ->where('item_id', $offer['item_id'])
                        ->decrement('quantity', $offer['quantity']);
                }

                $lockedTrade->update([
                    'to_user_id' => Auth::id(),
                    'status' => 'pending',
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Offer submitted successfully.');
    }

    private function validateTradePayload(Request $request): array
    {
        return $request->validate([
            'offer_items' => ['required', 'array', 'min:1'],
            'offer_items.*.item_id' => ['required', 'integer', 'exists:items,id'],
            'offer_items.*.quantity' => ['required', 'integer', 'min:1'],
            'wanted_items' => ['required', 'array', 'min:1'],
            'wanted_items.*.item_id' => ['required', 'integer', 'exists:items,id'],
            'wanted_items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);
    }

    private function normalizeLineItems(array $rows): Collection
    {
        return collect($rows)
            ->groupBy('item_id')
            ->map(fn ($group, $itemId) => [
                'item_id' => (int) $itemId,
                'quantity' => (int) $group->sum('quantity'),
            ])
            ->filter(fn ($row) => $row['quantity'] > 0)
            ->values();
    }

    private function assertInventoryAvailability(Collection $items, int $userId): void
    {
        foreach ($items as $item) {
            $inventoryRow = Player_inventory::query()
                ->where('user_id', $userId)
                ->where('item_id', $item['item_id'])
                ->lockForUpdate()
                ->first();

            if (! $inventoryRow || $inventoryRow->quantity < $item['quantity']) {
                throw new \RuntimeException('You do not have enough quantity for one of the selected items.');
            }
        }
    }

    private function syncTradeItems(Trade $trade, Collection $items): void
    {
        foreach ($items as $item) {
            TradeItem::create([
                'trade_id' => $trade->id,
                'item_id' => $item['item_id'],
                'from_user_id' => $trade->from_user_id,
                'quantity' => $item['quantity'],
            ]);
        }
    }

    private function syncTradeWants(Trade $trade, Collection $items): void
    {
        foreach ($items as $item) {
            TradeWant::create([
                'trade_id' => $trade->id,
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
            ]);
        }
    }
}
