<?php

namespace App\Livewire;

use App\Models\Trade;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminTradesPage extends Component
{
    public string $search = '';
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function deleteTrade(int $tradeId): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        Trade::findOrFail($tradeId)->delete();
        $this->successMessage = 'Trade deleted by admin.';
    }

    public function setTradeStatus(int $tradeId, string $status): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        if (! in_array($status, ['open', 'pending', 'accepted', 'rejected'], true)) {
            $this->errorMessage = 'Invalid trade status.';
            return;
        }

        $trade = Trade::findOrFail($tradeId);
        $trade->status = $status;

        if ($status === 'open') {
            $trade->to_user_id = null;
        }

        $trade->save();
        $this->successMessage = 'Trade status updated.';
        $this->errorMessage = null;
    }

    public function render(): \Illuminate\View\View
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $trades = Trade::query()
            ->with(['fromUser', 'toUser', 'wants.item', 'items.item'])
            ->when($this->search, function ($q) {
                $search = '%' . $this->search . '%';
                $q->where(function ($q) use ($search) {
                    $q->whereHas('fromUser', fn ($q) => $q->where('username', 'like', $search))
                        ->orWhereHas('toUser', fn ($q) => $q->where('username', 'like', $search))
                        ->orWhereHas('items.item', fn ($q) => $q->where('name', 'like', $search))
                        ->orWhereHas('wants.item', fn ($q) => $q->where('name', 'like', $search));
                });
            })
            ->latest()
            ->limit(150)
            ->get();

        return view('livewire.admin-trades-page', compact('trades'));
    }
}
