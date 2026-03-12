<nav class="mb-6 rounded-2xl border border-gray-200 bg-white p-2 shadow-sm">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.dashboard') }}"
              class="group inline-flex items-center rounded-xl px-4 py-2 text-sm font-medium transform-gpu transition duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-50 text-gray-700 hover:scale-105 hover:bg-gray-100 hover:text-gray-900 hover:border-gray-300 hover:shadow-sm' }}">
            <span class="transition {{ request()->routeIs('admin.dashboard') ? '' : 'group-hover:translate-x-0.5' }}">Overview</span>
        </a>
        <a href="{{ route('admin.users.index') }}"
              class="group inline-flex items-center rounded-xl px-4 py-2 text-sm font-medium transform-gpu transition duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-50 text-gray-700 hover:scale-105 hover:bg-gray-100 hover:text-gray-900 hover:border-gray-300 hover:shadow-sm' }}">
            <span class="transition {{ request()->routeIs('admin.users.*') ? '' : 'group-hover:translate-x-0.5' }}">Users</span>
        </a>
        <a href="{{ route('admin.items.index') }}"
              class="group inline-flex items-center rounded-xl px-4 py-2 text-sm font-medium transform-gpu transition duration-150 {{ request()->routeIs('admin.items.*') ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-50 text-gray-700 hover:scale-105 hover:bg-gray-100 hover:text-gray-900 hover:border-gray-300 hover:shadow-sm' }}">
            <span class="transition {{ request()->routeIs('admin.items.*') ? '' : 'group-hover:translate-x-0.5' }}">Items</span>
        </a>
        <a href="{{ route('admin.trades.index') }}"
              class="group inline-flex items-center rounded-xl px-4 py-2 text-sm font-medium transform-gpu transition duration-150 {{ request()->routeIs('admin.trades.*') ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-50 text-gray-700 hover:scale-105 hover:bg-gray-100 hover:text-gray-900 hover:border-gray-300 hover:shadow-sm' }}">
            <span class="transition {{ request()->routeIs('admin.trades.*') ? '' : 'group-hover:translate-x-0.5' }}">Trades</span>
        </a>
    </div>
</nav>
