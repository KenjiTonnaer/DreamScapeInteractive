<?php

namespace App\Livewire;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminItemFormPage extends Component
{
    public ?int $itemId = null;
    public string $name = '';
    public ?string $description = null;
    public string $type = 'misc';
    public string $rarity = 'common';
    public int $strength = 0;
    public int $speed = 0;
    public int $durability = 0;
    public ?string $magical_property = null;
    public int $required_level = 1;

    public ?string $successMessage = null;

    public function mount(?int $itemId = null): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        if (! $itemId) {
            return;
        }

        $item = Item::findOrFail($itemId);
        $this->itemId = $item->id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->type = $item->type;
        $this->rarity = $item->rarity;
        $this->strength = (int) $item->strength;
        $this->speed = (int) $item->speed;
        $this->durability = (int) $item->durability;
        $this->magical_property = $item->magical_property;
        $this->required_level = (int) $item->required_level;
    }

    public function save(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:items,name,' . ($this->itemId ?? 'NULL') . ',id'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:weapon,armor,consumable,misc'],
            'rarity' => ['required', 'in:common,uncommon,rare,epic,legendary'],
            'strength' => ['required', 'integer', 'min:0'],
            'speed' => ['required', 'integer', 'min:0'],
            'durability' => ['required', 'integer', 'min:0'],
            'magical_property' => ['nullable', 'string', 'max:255'],
            'required_level' => ['required', 'integer', 'min:1'],
        ]);

        if ($this->itemId) {
            Item::query()->whereKey($this->itemId)->update($validated);
            $this->successMessage = 'Item updated.';
            return;
        }

        $item = Item::create($validated);
        $this->itemId = $item->id;
        $this->successMessage = 'Item created.';
    }

    public function render(): \Illuminate\View\View
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        return view('livewire.admin-item-form-page');
    }
}
