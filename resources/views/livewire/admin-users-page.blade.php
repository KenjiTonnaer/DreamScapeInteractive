<div class="relative left-1/2 -translate-x-1/2 w-[calc(100vw-2rem)] max-w-[90rem] bg-white border border-gray-200 rounded-2xl p-8 md:p-12 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User Accounts</h1>
            <p class="text-gray-600">Manage users, roles, and account removal.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-50">Back to Admin</a>
            <a href="{{ route('admin.users.create') }}" class="px-3 py-2 text-sm rounded-md bg-black text-white hover:bg-gray-800">Create User</a>
        </div>
    </div>

    @if($successMessage)
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-800">{{ $successMessage }}</div>
    @endif
    @if($errorMessage)
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-800">{{ $errorMessage }}</div>
    @endif

    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..." class="w-full md:w-80 rounded-md border border-gray-300 px-3 py-2 text-sm">
    </div>

    <div class="overflow-auto rounded-xl border border-gray-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Level</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $user->username }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->role }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->level }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="px-2 py-1 text-xs rounded border border-gray-300 bg-white hover:bg-gray-100">Edit</a>
                                <button wire:click="toggleUserRole({{ $user->id }})" class="px-2 py-1 text-xs rounded border border-gray-300 bg-white hover:bg-gray-100">Toggle Role</button>
                                @if((int) $user->id !== (int) auth()->id())
                                    <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Delete this user account?" class="px-2 py-1 text-xs rounded border border-red-300 text-red-700 bg-white hover:bg-red-50">Delete</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
