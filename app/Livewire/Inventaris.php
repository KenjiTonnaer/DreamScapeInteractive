<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Player_inventory;
use App\Models\Trade;
use App\Models\TradeItem;
use App\Models\TradeWant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Inventaris extends Component
{
    public string $searchInventory = '';
    public string $searchTrades = '';
    public string $searchOffers = '';

    // Trade builder state
    public array $offerQty = [];
    public array $wantQty = [];
    public string $builderOfferSearch = '';
    public string $builderWantSearch = '';
    public ?int $editingTradeId = null;

    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function startCreate(): void
    {
        $this->editingTradeId = null;
        $this->offerQty = [];
        $this->wantQty = [];
        $this->builderOfferSearch = '';
        $this->builderWantSearch = '';
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->dispatch('open-dialog', id: 'trade-builder-modal');
    }

    public function startEdit(int $tradeId): void
    {
        $trade = Trade::with(['items', 'wants'])->findOrFail($tradeId);

        if ((int) $trade->from_user_id !== (int) Auth::id()) {
            return;
        }

        $this->editingTradeId = $tradeId;
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->builderOfferSearch = '';
        $this->builderWantSearch = '';

        $this->offerQty = $trade->items
            ->where('from_user_id', Auth::id())
            ->pluck('quantity', 'item_id')
            ->map(fn ($q) => (int) $q)
            ->toArray();

        $this->wantQty = $trade->wants
            ->pluck('quantity', 'item_id')
            ->map(fn ($q) => (int) $q)
            ->toArray();

        $this->dispatch('open-dialog', id: 'trade-builder-modal');
    }

    public function saveTrade(): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $offeredItems = collect($this->offerQty)
            ->filter(fn ($qty) => (int) $qty > 0)
            ->map(fn ($qty, $itemId) => ['item_id' => (int) $itemId, 'quantity' => (int) $qty])
            ->values();

        $wantedItems = collect($this->wantQty)
            ->filter(fn ($qty) => (int) $qty > 0)
            ->map(fn ($qty, $itemId) => ['item_id' => (int) $itemId, 'quantity' => (int) $qty])
            ->values();

        if ($offeredItems->isEmpty()) {
            $this->errorMessage = 'Select at least one item to offer (enter a quantity greater than 0).';
            return;
        }

        if ($wantedItems->isEmpty()) {
            $this->errorMessage = 'Select at least one item you want (enter a quantity greater than 0).';
            return;
        }

        $isEdit = $this->editingTradeId !== null;

        try {
            DB::transaction(function () use ($offeredItems, $wantedItems, $isEdit) {
                foreach ($offeredItems as $item) {
                    $inv = Player_inventory::query()
                        ->where('user_id', Auth::id())
                        ->where('item_id', $item['item_id'])
                        ->lockForUpdate()
                        ->first();

                    if (! $inv || $inv->quantity < $item['quantity']) {
                        throw new \RuntimeException('You do not have enough quantity for one of the selected items.');
                    }
                }

                if ($isEdit) {
                    $trade = Trade::findOrFail($this->editingTradeId);

                    if ((int) $trade->from_user_id !== (int) Auth::id()) {
                        throw new \RuntimeException('Unauthorized.');
                    }

                    if ($trade->status !== 'open' || ! is_null($trade->to_user_id)) {
                        throw new \RuntimeException('Only open trades can be edited.');
                    }

                    $trade->items()->delete();
                    $trade->wants()->delete();
                } else {
                    $trade = Trade::create([
                        'from_user_id' => Auth::id(),
                        'to_user_id' => null,
                        'status' => 'open',
                    ]);
                }

                foreach ($offeredItems as $item) {
                    TradeItem::create([
                        'trade_id' => $trade->id,
                        'item_id' => $item['item_id'],
                        'from_user_id' => Auth::id(),
                        'quantity' => $item['quantity'],
                    ]);
                }

                foreach ($wantedItems as $item) {
                    TradeWant::create([
                        'trade_id' => $trade->id,
                        'item_id' => $item['item_id'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            });
        } catch (\RuntimeException $e) {
            $this->errorMessage = $e->getMessage();
            return;
        }

        $this->successMessage = $isEdit ? 'Trade updated successfully.' : 'Trade created successfully.';
        $this->editingTradeId = null;
        $this->offerQty = [];
        $this->wantQty = [];
        $this->builderOfferSearch = '';
        $this->builderWantSearch = '';
        $this->dispatch('close-dialog', id: 'trade-builder-modal');
    }

    public function deleteTrade(int $tradeId): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $trade = Trade::findOrFail($tradeId);

        if ((int) $trade->from_user_id !== (int) Auth::id()) {
            return;
        }

        if ($trade->status !== 'open' || ! is_null($trade->to_user_id)) {
            $this->errorMessage = 'Only open trades can be deleted.';
            return;
        }

        $trade->delete();
        $this->successMessage = 'Trade deleted successfully.';
    }

    public function render(): \Illuminate\View\View
    {
        $inventaris = Player_inventory::query()
            ->with('item')
            ->where('user_id', Auth::id())
            ->where('quantity', '>', 0)
            ->when($this->searchInventory, function ($q) {
                $q->whereHas('item', fn ($q) => $q->where('name', 'like', '%' . $this->searchInventory . '%'));
            })
            ->orderByDesc('quantity')
            ->get();

        $myTrades = Trade::query()
            ->with(['fromUser', 'toUser', 'items.item', 'wants.item'])
            ->where('from_user_id', Auth::id())
            ->when($this->searchTrades, function ($q) {
                $search = '%' . $this->searchTrades . '%';
                $q->where(function ($q) use ($search) {
                    $q->whereHas('items.item', fn ($q) => $q->where('name', 'like', $search))
                      ->orWhereHas('wants.item', fn ($q) => $q->where('name', 'like', $search));
                });
            })
            ->latest()
            ->get();

        $myOfferedTrades = Trade::query()
            ->with(['fromUser', 'toUser', 'items.item', 'wants.item'])
            ->where('to_user_id', Auth::id())
            ->where('from_user_id', '!=', Auth::id())
            ->when($this->searchOffers, function ($q) {
                $search = '%' . $this->searchOffers . '%';
                $q->where(function ($q) use ($search) {
                    $q->whereHas('items.item', fn ($q) => $q->where('name', 'like', $search))
                      ->orWhereHas('wants.item', fn ($q) => $q->where('name', 'like', $search));
                });
            })
            ->latest()
            ->get();

        $allItems = Item::query()->orderBy('name')->get();

        return view('livewire.inventaris', compact('inventaris', 'myTrades', 'myOfferedTrades', 'allItems'));
    }
}
