<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render(): \Illuminate\View\View
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $stats = [
            'users' => User::count(),
            'items' => Item::count(),
            'openTrades' => Trade::where('status', 'open')->count(),
            'pendingTrades' => Trade::where('status', 'pending')->count(),
        ];

        return view('livewire.admin-dashboard', compact('stats'));
    }
}
