<div class="relative left-1/2 -translate-x-1/2 w-[calc(100vw-2rem)] max-w-[90rem] bg-white border border-gray-200 rounded-2xl p-8 md:p-12 shadow-sm">
    <div class="max-w-3xl mx-auto text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Admin Dashboard</h1>
        <p class="text-gray-700">Choose a management area below. Create pages and management lists are now split into separate admin pages.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4"><p class="text-xs text-gray-500">Users</p><p class="text-2xl font-bold">{{ $stats['users'] }}</p></div>
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4"><p class="text-xs text-gray-500">Items</p><p class="text-2xl font-bold">{{ $stats['items'] }}</p></div>
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4"><p class="text-xs text-gray-500">Open Trades</p><p class="text-2xl font-bold">{{ $stats['openTrades'] }}</p></div>
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4"><p class="text-xs text-gray-500">Pending Trades</p><p class="text-2xl font-bold">{{ $stats['pendingTrades'] }}</p></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <a href="{{ route('admin.users.index') }}" class="group rounded-xl border border-gray-200 bg-gray-50 p-5 transform-gpu transition duration-150 hover:scale-105 hover:-translate-y-0.5 hover:border-gray-300 hover:bg-gray-100 hover:shadow-sm">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Users</h2>
            <p class="text-sm text-gray-600 group-hover:text-gray-700">Search users, toggle roles and delete accounts.</p>
        </a>
        <a href="{{ route('admin.users.create') }}" class="group rounded-xl border border-gray-200 bg-gray-50 p-5 transform-gpu transition duration-150 hover:scale-105 hover:-translate-y-0.5 hover:border-gray-300 hover:bg-gray-100 hover:shadow-sm">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Create User</h2>
            <p class="text-sm text-gray-600 group-hover:text-gray-700">Create new admin or player accounts on a dedicated page.</p>
        </a>
        <a href="{{ route('admin.items.index') }}" class="group rounded-xl border border-gray-200 bg-gray-50 p-5 transform-gpu transition duration-150 hover:scale-105 hover:-translate-y-0.5 hover:border-gray-300 hover:bg-gray-100 hover:shadow-sm">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Items</h2>
            <p class="text-sm text-gray-600 group-hover:text-gray-700">Browse, search and delete existing catalog items.</p>
        </a>
        <a href="{{ route('admin.items.create') }}" class="group rounded-xl border border-gray-200 bg-gray-50 p-5 transform-gpu transition duration-150 hover:scale-105 hover:-translate-y-0.5 hover:border-gray-300 hover:bg-gray-100 hover:shadow-sm">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Create Item</h2>
            <p class="text-sm text-gray-600 group-hover:text-gray-700">Add new items through a full separate form page.</p>
        </a>
        <a href="{{ route('admin.trades.index') }}" class="group rounded-xl border border-gray-200 bg-gray-50 p-5 transform-gpu transition duration-150 hover:scale-105 hover:-translate-y-0.5 hover:border-gray-300 hover:bg-gray-100 hover:shadow-sm md:col-span-2 xl:col-span-1">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Trades</h2>
            <p class="text-sm text-gray-600 group-hover:text-gray-700">Moderate all trades, adjust statuses and remove entries.</p>
        </a>
    </div>
</div>
