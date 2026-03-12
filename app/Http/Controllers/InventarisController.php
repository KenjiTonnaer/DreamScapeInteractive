<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Player_inventory;
use App\Models\Trade;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InventarisController extends Controller
{
    public function index(): View
    {
        $inventaris = Player_inventory::query()
            ->with('item')
            ->where('user_id', Auth::id())
            ->where('quantity', '>', 0)
            ->orderByDesc('quantity')
            ->get();

        $myTrades = Trade::query()
            ->with(['fromUser', 'toUser', 'items.item', 'wants.item'])
            ->where('from_user_id', Auth::id())
            ->latest()
            ->get();

        $allItems = Item::query()
            ->orderBy('name')
            ->get();

        return view('user.inventaris', compact('inventaris', 'myTrades', 'allItems'));
    }
}
