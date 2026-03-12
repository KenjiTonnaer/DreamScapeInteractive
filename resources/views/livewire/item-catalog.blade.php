<div class="relative left-1/2 -translate-x-1/2 w-[calc(100vw-2rem)] max-w-[90rem] bg-white border border-gray-200 rounded-2xl p-8 md:p-12 shadow-sm">

    <div class="max-w-3xl mx-auto text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Item Catalog</h1>
        <p class="text-gray-700 leading-relaxed">Browse all available items and discover their properties.</p>
    </div>

    {{-- Search + Filter bar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-8">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search by name..."
            class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400"
        >
        <select
            wire:model.live="typeFilter"
            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400"
        >
            <option value="all">All types</option>
            @foreach($types as $type)
                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
            @endforeach
        </select>
        <select
            wire:model.live="rarityFilter"
            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400"
        >
            <option value="all">All rarities</option>
            @foreach($rarities as $rarity)
                <option value="{{ $rarity }}">{{ ucfirst($rarity) }}</option>
            @endforeach
        </select>
    </div>

    {{-- Results count --}}
    <p class="text-sm text-gray-500 mb-4">{{ $items->total() }} item{{ $items->total() !== 1 ? 's' : '' }} found</p>

    {{-- Items grid --}}
    @if($items->isEmpty())
        <p class="text-center text-gray-500 py-12">No items found matching your filters.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
            @foreach($items as $item)
                <article class="border border-gray-200 rounded-xl p-4 bg-gray-50 flex flex-col">
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $item->name }}</h3>
                            <p class="text-sm text-gray-500">{{ ucfirst($item->type) }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded bg-white border border-gray-200 whitespace-nowrap">
                            {{ ucfirst($item->rarity) }}
                        </span>
                    </div>

                    <div class="space-y-1 text-sm text-gray-700 mb-4">
                        <p><strong>Level:</strong> {{ $item->required_level }}</p>
                        <p class="line-clamp-2"><strong>Info:</strong> {{ $item->description ?? 'No description.' }}</p>
                    </div>

                    <div class="mt-auto">
                        <button
                            type="button"
                            onclick="document.getElementById('catalog-item-{{ $item->id }}').showModal()"
                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md border border-gray-300 bg-white text-gray-800 hover:bg-gray-100 transition"
                        >
                            View Details
                        </button>
                    </div>
                </article>

                {{-- Item detail modal --}}
                <dialog id="catalog-item-{{ $item->id }}" class="m-auto w-[calc(100vw-2rem)] max-w-2xl rounded-xl p-0 border border-gray-200 shadow-xl backdrop:bg-black/40">
                    <div class="border-b border-gray-200 px-5 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                            <p class="text-sm text-gray-500">{{ ucfirst($item->type) }} &mdash; {{ ucfirst($item->rarity) }}</p>
                        </div>
                        <button type="button" onclick="this.closest('dialog').close()" class="text-sm text-gray-500 hover:text-gray-800">Close</button>
                    </div>
                    <div class="px-5 py-5 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-3">
                                <p class="text-xs uppercase tracking-wide text-gray-500">Type</p>
                                <p class="text-base font-semibold text-gray-900">{{ ucfirst($item->type) }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-3">
                                <p class="text-xs uppercase tracking-wide text-gray-500">Rarity</p>
                                <p class="text-base font-semibold text-gray-900">{{ ucfirst($item->rarity) }}</p>
                            </div>
                            <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-3">
                                <p class="text-xs uppercase tracking-wide text-gray-500">Required Level</p>
                                <p class="text-base font-semibold text-gray-900">{{ $item->required_level }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <section class="rounded-xl border border-gray-200 bg-white p-4">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Combat Stats</h4>
                                <div class="space-y-3 text-sm text-gray-700">
                                    <div class="flex items-center justify-between">
                                        <span>Strength</span>
                                        <span class="font-semibold text-gray-900">{{ $item->strength }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>Speed</span>
                                        <span class="font-semibold text-gray-900">{{ $item->speed }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>Durability</span>
                                        <span class="font-semibold text-gray-900">{{ $item->durability }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>Magic Property</span>
                                        <span class="font-semibold text-gray-900">{{ $item->magical_property ?? '-' }}</span>
                                    </div>
                                </div>
                            </section>

                            <section class="rounded-xl border border-gray-200 bg-white p-4">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Description</h4>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $item->description ?? 'No description available.' }}</p>
                            </section>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" onclick="this.closest('dialog').close()" class="px-3 py-1.5 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-50">Close</button>
                        </div>
                    </div>
                </dialog>
            @endforeach
        </div>

        {{ $items->links() }}
    @endif
</div>
