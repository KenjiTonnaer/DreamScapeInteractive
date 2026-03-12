<div class="relative left-1/2 -translate-x-1/2 w-[calc(100vw-2rem)] max-w-[90rem] bg-white border border-gray-200 rounded-2xl p-8 md:p-12 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Trades Management</h1>
            <p class="text-gray-600">Moderate and remove trades from all users.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-50">Back to Admin</a>
    </div>

    @if($successMessage)
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-800">{{ $successMessage }}</div>
    @endif
    @if($errorMessage)
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-800">{{ $errorMessage }}</div>
    @endif

    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search trades..." class="w-full md:w-80 rounded-md border border-gray-300 px-3 py-2 text-sm">
    </div>

    <div class="max-h-112 overflow-auto divide-y divide-gray-200 border border-gray-200 rounded-lg bg-white">
        @forelse($trades as $trade)
            <div class="p-4 flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                <div class="text-sm">
                    <p class="font-medium text-gray-900">Trade #{{ $trade->id }} | {{ ucfirst($trade->status) }}</p>
                    <p class="text-gray-500">From: {{ $trade->fromUser?->username ?? 'Unknown' }} | To: {{ $trade->toUser?->username ?? 'Open' }} | {{ $trade->created_at?->format('d-m-Y H:i') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="setTradeStatus({{ $trade->id }}, 'open')" class="px-2 py-1 text-xs rounded border border-gray-300 bg-white hover:bg-gray-100">Set Open</button>
                    <button wire:click="setTradeStatus({{ $trade->id }}, 'pending')" class="px-2 py-1 text-xs rounded border border-gray-300 bg-white hover:bg-gray-100">Set Pending</button>
                    <button wire:click="setTradeStatus({{ $trade->id }}, 'accepted')" class="px-2 py-1 text-xs rounded border border-gray-300 bg-white hover:bg-gray-100">Set Accepted</button>
                    <button wire:click="deleteTrade({{ $trade->id }})" wire:confirm="Delete this trade?" class="px-2 py-1 text-xs rounded border border-red-300 text-red-700 bg-white hover:bg-red-50">Delete Trade</button>
                </div>
            </div>
        @empty
            <p class="p-4 text-sm text-gray-500">No trades found.</p>
        @endforelse
    </div>
</div>
