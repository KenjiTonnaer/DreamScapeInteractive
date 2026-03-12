<div class="relative left-1/2 -translate-x-1/2 w-[calc(100vw-2rem)] max-w-4xl bg-white border border-gray-200 rounded-2xl p-8 md:p-12 shadow-sm">
    <div class="flex items-center justify-between gap-3 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $itemId ? 'Edit Item' : 'Create Item' }}</h1>
            <p class="text-gray-600">{{ $itemId ? 'Update item details and stats.' : 'Add a new item to the catalog.' }}</p>
        </div>
        <a href="{{ route('admin.items.index') }}" class="px-3 py-2 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-50">Back to Items</a>
    </div>

    @if($successMessage)
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-800">{{ $successMessage }}</div>
    @endif

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" wire:model.defer="name" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea wire:model.defer="description" rows="4" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"></textarea>
            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select wire:model.defer="type" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <option value="weapon">Weapon</option>
                    <option value="armor">Armor</option>
                    <option value="consumable">Consumable</option>
                    <option value="misc">Misc</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rarity</label>
                <select wire:model.defer="rarity" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <option value="common">Common</option>
                    <option value="uncommon">Uncommon</option>
                    <option value="rare">Rare</option>
                    <option value="epic">Epic</option>
                    <option value="legendary">Legendary</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Required Level</label>
                <input type="number" min="1" wire:model.defer="required_level" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Strength</label>
                <input type="number" min="0" wire:model.defer="strength" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Speed</label>
                <input type="number" min="0" wire:model.defer="speed" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Durability</label>
                <input type="number" min="0" wire:model.defer="durability" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Magic Property</label>
                <input type="text" wire:model.defer="magical_property" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 text-sm rounded-md bg-black text-white hover:bg-gray-800">{{ $itemId ? 'Save Changes' : 'Create Item' }}</button>
        </div>
    </form>
</div>
