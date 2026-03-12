<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ItemCatalog extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $typeFilter = 'all';

    #[Url]
    public string $rarityFilter = 'all';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatedRarityFilter(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\View\View
    {
        $items = Item::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->typeFilter !== 'all', fn ($q) => $q->where('type', $this->typeFilter))
            ->when($this->rarityFilter !== 'all', fn ($q) => $q->where('rarity', $this->rarityFilter))
            ->orderBy('name')
            ->paginate(20);

        $types = Item::query()->distinct()->orderBy('type')->pluck('type')->filter()->values();
        $rarities = Item::query()->distinct()->orderBy('rarity')->pluck('rarity')->filter()->values();

        return view('livewire.item-catalog', compact('items', 'types', 'rarities'));
    }
}
