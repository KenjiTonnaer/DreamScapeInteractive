<div class="relative left-1/2 -translate-x-1/2 w-[calc(100vw-2rem)] max-w-[90rem] bg-white border border-gray-200 rounded-2xl p-8 md:p-12 shadow-sm">

    <div class="max-w-3xl mx-auto text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Your Inventory</h1>
        <p class="text-gray-700 leading-relaxed">Browse your collected items and manage your trades.</p>
    </div>

    {{-- Messages --}}
    @if($successMessage)
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
            {{ $successMessage }}
        </div>
    @endif
    @if($errorMessage)
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
            {{ $errorMessage }}
        </div>
    @endif

    {{-- ===== YOUR ITEMS ===== --}}
    <div class="mb-12">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Your Items</h2>
                <p class="text-sm text-gray-500">{{ $inventaris->count() }} item entries</p>
            </div>
            <input
                type="text"
                wire:model.live.debounce.300ms="searchInventory"
                placeholder="Search items..."
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 sm:w-64"
            >
        </div>

        @if($inventaris->isEmpty())
            <p class="text-center text-gray-500 py-8">
                @if($searchInventory)
                    No items found matching "{{ $searchInventory }}".
                @else
                    No items found in your inventory.
                @endif
            </p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach($inventaris as $entry)
                    <article class="border border-gray-200 rounded-xl p-4 bg-gray-50 flex flex-col">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $entry->item->name ?? 'Unknown item' }}</h3>
                                <p class="text-sm text-gray-500">{{ $entry->item->type ?? 'Unknown type' }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded bg-white border border-gray-200 whitespace-nowrap">
                                Qty {{ $entry->quantity }}
                            </span>
                        </div>

                        <div class="space-y-1 text-sm text-gray-700 mb-4">
                            <p><strong>Rarity:</strong> {{ $entry->item->rarity ?? 'Unknown' }}</p>
                            <p><strong>Level:</strong> {{ $entry->item->required_level ?? '-' }}</p>
                            <p class="line-clamp-2"><strong>Info:</strong> {{ $entry->item->description ?? 'No description.' }}</p>
                        </div>

                        <div class="mt-auto">
                            <button
                                type="button"
                                onclick="document.getElementById('inventory-item-{{ $entry->id }}').showModal()"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md border border-gray-300 bg-white text-gray-800 hover:bg-gray-100 transition"
                            >
                                View Details
                            </button>
                        </div>
                    </article>

                    {{-- Item detail modal (static, no Livewire needed) --}}
                    <dialog id="inventory-item-{{ $entry->id }}" class="m-auto w-[calc(100vw-2rem)] max-w-2xl rounded-xl p-0 border border-gray-200 shadow-xl backdrop:bg-black/40">
                        <div class="border-b border-gray-200 px-5 py-4 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $entry->item->name ?? 'Unknown item' }}</h3>
                                <p class="text-sm text-gray-500">
                                    {{ ucfirst($entry->item->type ?? 'unknown type') }}
                                    <span class="mx-1">|</span>
                                    {{ ucfirst($entry->item->rarity ?? 'unknown rarity') }}
                                </p>
                            </div>
                            <button type="button" onclick="this.closest('dialog').close()" class="text-sm text-gray-500 hover:text-gray-800">Close</button>
                        </div>
                        <div class="px-5 py-5 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-3">
                                    <p class="text-xs uppercase tracking-wide text-gray-500">Owned Quantity</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $entry->quantity }}</p>
                                </div>
                                <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-3">
                                    <p class="text-xs uppercase tracking-wide text-gray-500">Required Level</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $entry->item->required_level ?? '-' }}</p>
                                </div>
                                <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-3">
                                    <p class="text-xs uppercase tracking-wide text-gray-500">Magic Property</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $entry->item->magical_property ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <section class="rounded-xl border border-gray-200 bg-white p-4">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Combat Stats</h4>
                                    <div class="space-y-3 text-sm text-gray-700">
                                        <div class="flex items-center justify-between">
                                            <span>Strength</span>
                                            <span class="font-semibold text-gray-900">{{ $entry->item->strength ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span>Speed</span>
                                            <span class="font-semibold text-gray-900">{{ $entry->item->speed ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span>Durability</span>
                                            <span class="font-semibold text-gray-900">{{ $entry->item->durability ?? '-' }}</span>
                                        </div>
                                    </div>
                                </section>

                                <section class="rounded-xl border border-gray-200 bg-white p-4">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Description</h4>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        {{ $entry->item->description ?? 'No description available.' }}
                                    </p>
                                </section>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" onclick="this.closest('dialog').close()" class="px-3 py-1.5 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-50">Close</button>
                            </div>
                        </div>
                    </dialog>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ===== MY CREATED TRADES ===== --}}
    <div class="mb-12">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">My Created Trades</h2>
                <p class="text-sm text-gray-500">{{ $myTrades->count() }} trades</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchTrades"
                    placeholder="Search trades..."
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 sm:w-56"
                >
                <button
                    wire:click="startCreate"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm rounded-lg bg-black text-white hover:bg-gray-800 transition"
                >
                    Create Trade
                </button>
            </div>
        </div>

        @if($myTrades->isEmpty())
            <p class="text-center text-gray-500 py-8">
                @if($searchTrades)
                    No trades found matching "{{ $searchTrades }}".
                @else
                    You have not created any trades yet.
                @endif
            </p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach($myTrades as $trade)
                    <article class="border border-gray-200 rounded-xl p-4 bg-gray-50 flex flex-col">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-900">Trade #{{ $trade->id }}</h3>
                            <span class="text-xs px-2 py-1 rounded bg-white border border-gray-200">{{ ucfirst($trade->status) }}</span>
                        </div>

                        <p class="text-sm text-gray-700 mb-2">
                            <strong>Target:</strong>
                            {{ $trade->toUser?->name ?? $trade->toUser?->email ?? ($trade->status === 'pending' ? 'Pending review' : 'Open trade') }}
                        </p>

                        <div class="mb-3">
                            <p class="text-sm font-medium text-gray-900 mb-1">Offered Items</p>
                            <ul class="text-sm text-gray-700 space-y-0.5">
                                @forelse($trade->items->where('from_user_id', $trade->from_user_id) as $tradeItem)
                                    <li>{{ $tradeItem->item->name ?? 'Unknown' }} x{{ $tradeItem->quantity }}</li>
                                @empty
                                    <li class="text-gray-500">None listed.</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-900 mb-1">Wanted Items</p>
                            <ul class="text-sm text-gray-700 space-y-0.5">
                                @forelse($trade->wants as $want)
                                    <li>{{ $want->item->name ?? 'Unknown' }} x{{ $want->quantity }}</li>
                                @empty
                                    <li class="text-gray-500">None listed.</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="mt-auto flex flex-wrap gap-2">
                            <button
                                type="button"
                                onclick="document.getElementById('view-trade-{{ $trade->id }}').showModal()"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md border border-gray-300 bg-white text-gray-800 hover:bg-gray-100 transition"
                            >
                                View
                            </button>

                            @if($trade->status === 'open' && is_null($trade->to_user_id))
                                <button
                                    wire:click="startEdit({{ $trade->id }})"
                                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md border border-gray-300 bg-white text-gray-800 hover:bg-gray-100 transition"
                                >
                                    Edit
                                </button>

                                <button
                                    wire:click="deleteTrade({{ $trade->id }})"
                                    wire:confirm="Delete this trade?"
                                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md border border-red-300 bg-white text-red-700 hover:bg-red-50 transition"
                                >
                                    Delete
                                </button>
                            @endif
                        </div>
                    </article>

                    {{-- View Trade modal (static) --}}
                    <dialog id="view-trade-{{ $trade->id }}" class="m-auto w-[calc(100vw-2rem)] max-w-2xl rounded-xl p-0 border border-gray-200 shadow-xl backdrop:bg-black/40">
                        <div class="border-b border-gray-200 px-5 py-4 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Trade #{{ $trade->id }}</h3>
                                <p class="text-sm text-gray-500">{{ ucfirst($trade->status) }}</p>
                            </div>
                            <button type="button" onclick="this.closest('dialog').close()" class="text-sm text-gray-500 hover:text-gray-800">Close</button>
                        </div>
                        <div class="px-5 py-5 space-y-4">
                            <div class="grid md:grid-cols-2 gap-3">
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-sm font-medium text-gray-900 mb-1">Creator</p>
                                    <p class="text-sm text-gray-700">{{ $trade->fromUser?->name ?? $trade->fromUser?->email ?? 'Unknown' }}</p>
                                </div>
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-sm font-medium text-gray-900 mb-1">Target Player</p>
                                    <p class="text-sm text-gray-700">{{ $trade->toUser?->name ?? $trade->toUser?->email ?? ($trade->status === 'pending' ? 'Pending review' : 'Open trade') }}</p>
                                </div>
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-sm font-medium text-gray-900 mb-1">Created</p>
                                    <p class="text-sm text-gray-700">{{ $trade->created_at?->format('d-m-Y H:i') }}</p>
                                </div>
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-sm font-medium text-gray-900 mb-1">Status</p>
                                    <p class="text-sm text-gray-700">{{ ucfirst($trade->status) }}</p>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 gap-3">
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-sm font-medium text-gray-900 mb-2">Offered Items</p>
                                    <ul class="text-sm text-gray-700 space-y-0.5">
                                        @forelse($trade->items->where('from_user_id', $trade->from_user_id) as $tradeItem)
                                            <li>{{ $tradeItem->item->name ?? 'Unknown' }} x{{ $tradeItem->quantity }}</li>
                                        @empty
                                            <li class="text-gray-500">None listed.</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-sm font-medium text-gray-900 mb-2">Wanted Items</p>
                                    <ul class="text-sm text-gray-700 space-y-0.5">
                                        @forelse($trade->wants as $want)
                                            <li>{{ $want->item->name ?? 'Unknown' }} x{{ $want->quantity }}</li>
                                        @empty
                                            <li class="text-gray-500">None listed.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" onclick="this.closest('dialog').close()" class="px-3 py-1.5 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-50">Close</button>
                            </div>
                        </div>
                    </dialog>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ===== MY OFFERED TRADES ===== --}}
    <div>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">My Offered Trades</h2>
                <p class="text-sm text-gray-500">{{ $myOfferedTrades->count() }} offers</p>
            </div>
            <input
                type="text"
                wire:model.live.debounce.300ms="searchOffers"
                placeholder="Search offered trades..."
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 sm:w-64"
            >
        </div>

        @if($myOfferedTrades->isEmpty())
            <p class="text-center text-gray-500 py-8">
                @if($searchOffers)
                    No offered trades found matching "{{ $searchOffers }}".
                @else
                    You have not offered on any trades yet.
                @endif
            </p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach($myOfferedTrades as $trade)
                    <article class="border border-gray-200 rounded-xl p-4 bg-gray-50 flex flex-col">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-900">Trade #{{ $trade->id }}</h3>
                            <span class="text-xs px-2 py-1 rounded bg-white border border-gray-200">{{ ucfirst($trade->status) }}</span>
                        </div>

                        <p class="text-sm text-gray-700 mb-3">
                            <strong>Creator:</strong>
                            {{ $trade->fromUser?->name ?? $trade->fromUser?->email ?? 'Unknown' }}
                        </p>

                        <div class="mb-3">
                            <p class="text-sm font-medium text-gray-900 mb-1">Your Offer</p>
                            <ul class="text-sm text-gray-700 space-y-0.5">
                                @forelse($trade->items->where('from_user_id', auth()->id()) as $tradeItem)
                                    <li>{{ $tradeItem->item->name ?? 'Unknown' }} x{{ $tradeItem->quantity }}</li>
                                @empty
                                    <li class="text-gray-500">No offer lines found.</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-900 mb-1">Requested By Creator</p>
                            <ul class="text-sm text-gray-700 space-y-0.5">
                                @forelse($trade->wants as $want)
                                    <li>{{ $want->item->name ?? 'Unknown' }} x{{ $want->quantity }}</li>
                                @empty
                                    <li class="text-gray-500">No requested items.</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="mt-auto">
                            <button
                                type="button"
                                onclick="document.getElementById('view-offered-trade-{{ $trade->id }}').showModal()"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md border border-gray-300 bg-white text-gray-800 hover:bg-gray-100 transition"
                            >
                                View
                            </button>
                        </div>
                    </article>

                    <dialog id="view-offered-trade-{{ $trade->id }}" class="m-auto w-[calc(100vw-2rem)] max-w-2xl rounded-xl p-0 border border-gray-200 shadow-xl backdrop:bg-black/40">
                        <div class="border-b border-gray-200 px-5 py-4 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Offered Trade #{{ $trade->id }}</h3>
                                <p class="text-sm text-gray-500">{{ ucfirst($trade->status) }}</p>
                            </div>
                            <button type="button" onclick="this.closest('dialog').close()" class="text-sm text-gray-500 hover:text-gray-800">Close</button>
                        </div>
                        <div class="px-5 py-5 space-y-4">
                            <div class="grid md:grid-cols-2 gap-3">
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-sm font-medium text-gray-900 mb-1">Creator</p>
                                    <p class="text-sm text-gray-700">{{ $trade->fromUser?->name ?? $trade->fromUser?->email ?? 'Unknown' }}</p>
                                </div>
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-sm font-medium text-gray-900 mb-1">Status</p>
                                    <p class="text-sm text-gray-700">{{ ucfirst($trade->status) }}</p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-3">
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-sm font-medium text-gray-900 mb-2">Your Offer</p>
                                    <ul class="text-sm text-gray-700 space-y-0.5">
                                        @forelse($trade->items->where('from_user_id', auth()->id()) as $tradeItem)
                                            <li>{{ $tradeItem->item->name ?? 'Unknown' }} x{{ $tradeItem->quantity }}</li>
                                        @empty
                                            <li class="text-gray-500">No offer lines found.</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-sm font-medium text-gray-900 mb-2">Requested By Creator</p>
                                    <ul class="text-sm text-gray-700 space-y-0.5">
                                        @forelse($trade->wants as $want)
                                            <li>{{ $want->item->name ?? 'Unknown' }} x{{ $want->quantity }}</li>
                                        @empty
                                            <li class="text-gray-500">No requested items.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" onclick="this.closest('dialog').close()" class="px-3 py-1.5 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-50">Close</button>
                            </div>
                        </div>
                    </dialog>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ===== CREATE / EDIT TRADE MODAL (Livewire-driven) ===== --}}
    <dialog id="trade-builder-modal" wire:ignore.self class="m-auto w-[calc(100vw-2rem)] max-w-3xl rounded-xl p-0 border border-gray-200 shadow-xl backdrop:bg-black/40">
        <div class="border-b border-gray-200 px-5 py-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">
                {{ $editingTradeId ? 'Edit Trade #' . $editingTradeId : 'Create Trade' }}
            </h3>
            <button type="button" onclick="this.closest('dialog').close()" class="text-sm text-gray-500 hover:text-gray-800">
                Close
            </button>
        </div>

        <div class="px-5 py-5">
            @if($errorMessage)
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2 text-sm text-red-800">
                    {{ $errorMessage }}
                </div>
            @endif

            <form wire:submit="saveTrade" class="space-y-5">
                @php
                    $allItemsById = $allItems->keyBy('id');
                    $inventarisByItemId = $inventaris->keyBy('item_id');
                    $filteredInventarisForBuilder = $inventaris->filter(function ($entry) {
                        $name = strtolower((string) ($entry->item->name ?? ''));
                        $needle = strtolower(trim($this->builderOfferSearch));

                        return $needle === '' || str_contains($name, $needle);
                    });
                    $filteredWantedItemsForBuilder = $allItems->filter(function ($item) {
                        $name = strtolower((string) ($item->name ?? ''));
                        $needle = strtolower(trim($this->builderWantSearch));

                        return $needle === '' || str_contains($name, $needle);
                    });
                @endphp

                <div class="grid md:grid-cols-2 gap-5">
                    {{-- Offered Items --}}
                    <div>
                        <p class="text-sm font-medium text-gray-900 mb-2">Items You're Offering</p>
                        <p class="text-xs text-gray-500 mb-2">Enter a quantity > 0 to include the item.</p>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="builderOfferSearch"
                            placeholder="Search offered items..."
                            class="mb-3 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400"
                        >

                        @if($filteredInventarisForBuilder->isEmpty())
                            <p class="text-sm text-gray-500 mb-2">No inventory items found for this search.</p>
                        @endif

                        <div class="space-y-2 max-h-64 overflow-auto pr-1">
                            @foreach($filteredInventarisForBuilder as $entry)
                                <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white px-3 py-2">
                                    <span class="text-sm text-gray-800">
                                        {{ $entry->item->name ?? 'Unknown' }}
                                        <span class="text-xs text-gray-500">(own: {{ $entry->quantity }})</span>
                                    </span>
                                    <input
                                        type="number"
                                        wire:model.defer="offerQty.{{ $entry->item_id }}"
                                        min="0"
                                        max="{{ $entry->quantity }}"
                                        placeholder="0"
                                        class="w-20 rounded-md border border-gray-300 px-2 py-1 text-sm text-center"
                                    >
                                </div>
                            @endforeach
                        </div>

                        {{-- Live preview --}}
                        @php
                            $offerPreview = collect($offerQty)->filter(fn ($q) => (int) $q > 0);
                        @endphp
                        @if($offerPreview->isNotEmpty())
                            <div class="mt-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
                                <p class="text-sm font-medium text-gray-900 mb-1">Offer preview:</p>
                                <ul class="text-sm text-gray-700 space-y-0.5">
                                    @foreach($offerPreview as $itemId => $qty)
                                        <li>{{ $inventarisByItemId[$itemId]?->item?->name ?? 'Item #'.$itemId }} x{{ $qty }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    {{-- Wanted Items --}}
                    <div>
                        <p class="text-sm font-medium text-gray-900 mb-2">Items You Want</p>
                        <p class="text-xs text-gray-500 mb-2">Enter a quantity > 0 to request the item.</p>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="builderWantSearch"
                            placeholder="Search wanted items..."
                            class="mb-3 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400"
                        >

                        @if($filteredWantedItemsForBuilder->isEmpty())
                            <p class="text-sm text-gray-500 mb-2">No catalog items found for this search.</p>
                        @endif

                        <div class="space-y-2 max-h-64 overflow-auto pr-1">
                            @foreach($filteredWantedItemsForBuilder as $item)
                                <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white px-3 py-2">
                                    <span class="text-sm text-gray-800">{{ $item->name }}</span>
                                    <input
                                        type="number"
                                        wire:model.defer="wantQty.{{ $item->id }}"
                                        min="0"
                                        placeholder="0"
                                        class="w-20 rounded-md border border-gray-300 px-2 py-1 text-sm text-center"
                                    >
                                </div>
                            @endforeach
                        </div>

                        {{-- Live preview --}}
                        @php
                            $wantPreview = collect($wantQty)->filter(fn ($q) => (int) $q > 0);
                        @endphp
                        @if($wantPreview->isNotEmpty())
                            <div class="mt-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
                                <p class="text-sm font-medium text-gray-900 mb-1">Want preview:</p>
                                <ul class="text-sm text-gray-700 space-y-0.5">
                                    @foreach($wantPreview as $itemId => $qty)
                                        <li>{{ $allItemsById[$itemId]?->name ?? 'Item #'.$itemId }} x{{ $qty }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="this.closest('dialog').close()" class="px-3 py-1.5 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-3 py-1.5 text-sm rounded-md bg-black text-white hover:bg-gray-800">
                        {{ $editingTradeId ? 'Save Changes' : 'Create Trade' }}
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-dialog', ({ id }) => document.getElementById(id)?.showModal());
            Livewire.on('close-dialog', ({ id }) => document.getElementById(id)?.close());
        });
    </script>
</div>
