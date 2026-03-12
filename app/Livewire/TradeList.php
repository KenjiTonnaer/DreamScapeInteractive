<?php

namespace App\Livewire;

use App\Models\Player_inventory;
use App\Models\Trade;
use App\Models\TradeItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TradeList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = 'all';

    #[Url]
    public bool $canBidOnly = false;

    public array $offerQty = [];
    public string $offerSearch = '';
    public ?int $offeringTradeId = null;

    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedCanBidOnly(): void
    {
        $this->resetPage();
    }

    public function startOffer(int $tradeId): void
    {
        $this->offeringTradeId = $tradeId;
        $this->offerQty = [];
        $this->offerSearch = '';
        $this->errorMessage = null;
        $this->dispatch('open-dialog', id: 'offer-modal');
    }

    public function closeOffer(): void
    {
        $this->offeringTradeId = null;
        $this->offerQty = [];
        $this->offerSearch = '';
        $this->errorMessage = null;

        $this->dispatch('close-dialog', id: 'offer-modal');
        $this->dispatch('close-all-dialogs');
    }

    public function submitOffer(): void
    {
        $this->errorMessage = null;

        if (! $this->offeringTradeId) {
            return;
        }

        $offers = collect($this->offerQty)
            ->filter(fn ($qty) => (int) $qty > 0)
            ->map(fn ($qty, $itemId) => ['item_id' => (int) $itemId, 'quantity' => (int) $qty])
            ->values();

        if ($offers->isEmpty()) {
            $this->errorMessage = 'Select at least one item to offer.';
            return;
        }

        $trade = Trade::find($this->offeringTradeId);

        if (! $trade || $trade->status !== 'open' || ! is_null($trade->to_user_id)) {
            $this->errorMessage = 'Trade is no longer available.';
            return;
        }

        if ((int) $trade->from_user_id === (int) Auth::id()) {
            $this->errorMessage = 'You cannot place an offer on your own trade.';
            return;
        }

        try {
            DB::transaction(function () use ($offers) {
                $lockedTrade = Trade::query()
                    ->with('wants')
                    ->whereKey($this->offeringTradeId)
                    ->whereNull('to_user_id')
                    ->where('status', 'open')
                    ->lockForUpdate()
                    ->first();

                if (! $lockedTrade) {
                    throw new \RuntimeException('Trade is no longer available.');
                }

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

                foreach ($offers as $offer) {
                    $inv = Player_inventory::query()
                        ->where('user_id', Auth::id())
                        ->where('item_id', $offer['item_id'])
                        ->lockForUpdate()
                        ->first();

                    if (! $inv || $inv->quantity < $offer['quantity']) {
                        throw new \RuntimeException('Not enough quantity for one of the selected items.');
                    }
                }

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
            $this->errorMessage = $e->getMessage();
            return;
        }

        $this->offeringTradeId = null;
        $this->offerQty = [];
        $this->offerSearch = '';
        $this->successMessage = 'Offer submitted successfully.';
        $this->dispatch('close-dialog', id: 'offer-modal');
        $this->dispatch('close-all-dialogs');
    }

    public function render(): \Illuminate\View\View
    {
        $myInventory = Player_inventory::query()
            ->with('item')
            ->where('user_id', Auth::id())
            ->where('quantity', '>', 0)
            ->orderByDesc('quantity')
            ->get();

        $ownedItemIds = $myInventory
            ->pluck('item_id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $trades = Trade::query()
            ->with(['fromUser', 'toUser', 'items.item', 'wants.item'])
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function ($q) {
                $search = '%' . $this->search . '%';
                $q->where(function ($q) use ($search) {
                    $q->whereHas('items.item', fn ($q) => $q->where('name', 'like', $search))
                      ->orWhereHas('wants.item', fn ($q) => $q->where('name', 'like', $search));
                });
            })
            ->when($this->canBidOnly, function ($q) use ($ownedItemIds) {
                $q->where('status', 'open')
                  ->whereNull('to_user_id')
                  ->where('from_user_id', '!=', Auth::id());

                if (empty($ownedItemIds)) {
                    $q->whereRaw('1 = 0');
                    return;
                }

                $q->whereHas('wants', fn ($q) => $q->whereIn('item_id', $ownedItemIds));
            })
            ->latest()
            ->paginate(12);

        $offerTrade = $this->offeringTradeId
            ? Trade::with(['wants.item'])->find($this->offeringTradeId)
            : null;

        return view('livewire.trade-list', compact('trades', 'myInventory', 'offerTrade'));
    }
}
