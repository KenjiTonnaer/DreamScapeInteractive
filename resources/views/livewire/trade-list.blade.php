<div class="relative left-1/2 -translate-x-1/2 w-[calc(100vw-2rem)] max-w-[90rem] bg-white border border-gray-200 rounded-2xl p-8 md:p-12 shadow-sm">

    {{-- Flash messages --}}
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

    <div class="max-w-3xl mx-auto text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Trade Marketplace</h1>
        <p class="text-gray-700 leading-relaxed">Browse open trades and place an offer on items you want.</p>
    </div>

    {{-- Search + Filter bar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-8">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search by item name..."
            class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400"
        >
        <select
            wire:model.live="statusFilter"
            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400"
        >
            <option value="all">All statuses</option>
            <option value="open">Open</option>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
        </select>
        <label class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 bg-white">
            <input type="checkbox" wire:model.live="canBidOnly" class="rounded border-gray-300 text-black focus:ring-gray-400">
            Only tradable with my items
        </label>
    </div>

    {{-- Trades grid --}}
    @if($trades->isEmpty())
        <p class="text-center text-gray-500 py-12">No trades found.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
            @foreach($trades as $trade)
                <article class="border border-gray-200 rounded-xl p-4 bg-gray-50 flex flex-col">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-900">Trade #{{ $trade->id }}</h3>
                        <span class="text-xs px-2 py-1 rounded bg-white border border-gray-200">
                            {{ ucfirst($trade->status) }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-500 mb-3">
                        By {{ $trade->fromUser?->name ?? $trade->fromUser?->email ?? 'Unknown' }}
                    </p>

                    <div class="mb-3">
                        <p class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-1">Offered</p>
                        <ul class="text-sm text-gray-700 space-y-0.5">
                            @forelse($trade->items->where('from_user_id', $trade->from_user_id) as $tradeItem)
                                <li>{{ $tradeItem->item->name ?? 'Unknown' }} x{{ $tradeItem->quantity }}</li>
                            @empty
                                <li class="text-gray-500">No items listed.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-700 uppercase tracking-wide mb-1">Wanted</p>
                        <ul class="text-sm text-gray-700 space-y-0.5">
                            @forelse($trade->wants as $want)
                                <li>{{ $want->item->name ?? 'Unknown' }} x{{ $want->quantity }}</li>
                            @empty
                                <li class="text-gray-500">No requests listed.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="mt-auto">
                        @if($trade->status === 'open' && is_null($trade->to_user_id) && (int) $trade->from_user_id !== auth()->id())
                            <button
                                wire:click="startOffer({{ $trade->id }})"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-black text-white hover:bg-gray-800 transition"
                            >
                                Place Offer
                            </button>
                        @elseif((int) $trade->from_user_id === auth()->id())
                            <span class="text-xs text-gray-500 italic">Your trade</span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        {{ $trades->links() }}
    @endif

    {{-- Place Offer modal --}}
    <dialog id="offer-modal" wire:ignore.self class="m-auto w-[calc(100vw-2rem)] max-w-2xl rounded-xl p-0 border border-gray-200 shadow-xl backdrop:bg-black/40">
        @if($offerTrade)
            <div class="border-b border-gray-200 px-5 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Place Offer — Trade #{{ $offerTrade->id }}</h3>
                    <p class="text-sm text-gray-500">Enter quantities for the items you want to offer</p>
                </div>
                <button type="button" wire:click="closeOffer" class="text-sm text-gray-500 hover:text-gray-800">
                    Close
                </button>
            </div>

            <div class="px-5 py-5 space-y-4">
                @if($errorMessage)
                    <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-2 text-sm text-red-800">
                        {{ $errorMessage }}
                    </div>
                @endif

                <div>
                    <p class="text-sm font-medium text-gray-900 mb-1">They are asking for:</p>
                    <ul class="text-sm text-gray-700 space-y-0.5">
                        @foreach($offerTrade->wants as $want)
                            <li>{{ $want->item->name ?? 'Unknown' }} x{{ $want->quantity }}</li>
                        @endforeach
                    </ul>
                </div>

                @php
                    $wantedIds = $offerTrade->wants->pluck('item_id')->map(fn ($id) => (int) $id)->all();
                    $matchingInventory = $myInventory->filter(fn ($e) => in_array((int) $e->item_id, $wantedIds));
                    $filteredMatchingInventory = $matchingInventory->filter(function ($entry) {
                        $name = strtolower((string) ($entry->item->name ?? ''));
                        $needle = strtolower(trim($this->offerSearch));

                        return $needle === '' || str_contains($name, $needle);
                    });
                @endphp

                @if($matchingInventory->isEmpty())
                    <p class="text-sm text-gray-500">You don't own any of the wanted items.</p>
                @else
                    <div>
                        <p class="text-sm font-medium text-gray-900 mb-2">Your matching items (enter 0 to skip):</p>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="offerSearch"
                            placeholder="Search in your matching items..."
                            class="mb-3 w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400"
                        >

                        @if($filteredMatchingInventory->isEmpty())
                            <p class="text-sm text-gray-500">No matching inventory items for this search.</p>
                        @endif

                        <div class="space-y-2">
                            @foreach($filteredMatchingInventory as $entry)
                                <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white px-3 py-2">
                                    <span class="text-sm text-gray-800">
                                        {{ $entry->item->name ?? 'Unknown' }}
                                        <span class="text-gray-500">(you own: {{ $entry->quantity }})</span>
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
                    </div>

                    {{-- Preview --}}
                    @php
                        $offerPreview = collect($offerQty)->filter(fn ($q) => (int) $q > 0);
                    @endphp
                    @if($offerPreview->isNotEmpty())
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                            <p class="text-sm font-medium text-gray-900 mb-1">Your offer:</p>
                            <ul class="text-sm text-gray-700 space-y-0.5">
                                @foreach($offerPreview as $itemId => $qty)
                                    @php $invEntry = $myInventory->firstWhere('item_id', $itemId); @endphp
                                    <li>{{ $invEntry?->item?->name ?? 'Item #'.$itemId }} x{{ $qty }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="closeOffer" class="px-3 py-1.5 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="submitOffer"
                        class="px-3 py-1.5 text-sm rounded-md bg-black text-white hover:bg-gray-800"
                    >
                        Submit Offer
                    </button>
                </div>
            </div>
        @else
            <div class="px-5 py-5 text-sm text-gray-500">Loading...</div>
        @endif
    </dialog>

    <script>
        document.addEventListener('livewire:initialized', () => {
            if (window.tradeListDialogHandlersRegistered) {
                return;
            }

            window.tradeListDialogHandlersRegistered = true;

            Livewire.on('open-dialog', ({ id }) => {
                const dialog = document.getElementById(id);
                if (dialog && !dialog.open) {
                    dialog.showModal();
                }
            });

            Livewire.on('close-dialog', ({ id }) => {
                const dialog = document.getElementById(id);
                if (dialog?.open) {
                    dialog.close();
                }
            });

            Livewire.on('close-all-dialogs', () => {
                document.querySelectorAll('dialog[open]').forEach((dialog) => dialog.close());
            });
        });
    </script>
</div>
