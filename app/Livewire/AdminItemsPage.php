<?php

namespace App\Livewire;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminItemsPage extends Component
{
    public string $search = '';
    public ?string $successMessage = null;

    public function deleteItem(int $itemId): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        Item::findOrFail($itemId)->delete();
        $this->successMessage = 'Item deleted.';
    }

    public function render(): \Illuminate\View\View
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $items = Item::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->limit(150)
            ->get();

        return view('livewire.admin-items-page', compact('items'));
    }
}
