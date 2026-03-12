<div class="relative left-1/2 -translate-x-1/2 w-[calc(100vw-2rem)] max-w-5xl bg-white border border-gray-200 rounded-2xl p-8 md:p-12 shadow-sm">
    <div class="flex items-center justify-between gap-3 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $userId ? 'Edit User Account' : 'Create User Account' }}</h1>
            <p class="text-gray-600">{{ $userId ? 'Update an existing user account.' : 'Add a new user or admin account manually.' }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-sm rounded-md border border-gray-300 bg-white hover:bg-gray-50">Back to Users</a>
    </div>

    @if($successMessage)
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-800">{{ $successMessage }}</div>
    @endif

    <form wire:submit="save" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" wire:model.defer="username" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" wire:model.defer="email" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" wire:model.defer="password" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                @if($userId)
                    <p class="mt-1 text-xs text-gray-500">Leave empty to keep the current password.</p>
                @endif
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select wire:model.defer="role" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="max-w-xs">
            <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
            <input type="number" min="1" wire:model.defer="level" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('level') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 text-sm rounded-md bg-black text-white hover:bg-gray-800">{{ $userId ? 'Save Changes' : 'Create User' }}</button>
        </div>
    </form>

    @if($userId)
        <div class="mt-10 grid grid-cols-1 xl:grid-cols-2 gap-6">
            <section class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Grant Item</h2>
                <form wire:submit="giveItemToUser" class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                        <select wire:model.defer="grantItemId" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm bg-white">
                            <option value="">Select item...</option>
                            @foreach($allItems as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ ucfirst($item->rarity) }})</option>
                            @endforeach
                        </select>
                        @error('grantItemId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="max-w-xs">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" min="1" wire:model.defer="grantQuantity" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm bg-white">
                        @error('grantQuantity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 text-sm rounded-md bg-black text-white hover:bg-gray-800">Give Item</button>
                </form>
            </section>

            <section class="rounded-xl border border-gray-200 bg-white p-5">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Full Inventory</h2>

                @if($inventory->isEmpty())
                    <p class="text-sm text-gray-500">This player has no items yet.</p>
                @else
                    <div class="max-h-96 overflow-auto rounded-lg border border-gray-200">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-left text-gray-600">
                                <tr>
                                    <th class="px-3 py-2">Item</th>
                                    <th class="px-3 py-2">Type</th>
                                    <th class="px-3 py-2">Rarity</th>
                                    <th class="px-3 py-2">Qty</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($inventory as $row)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900">{{ $row->item->name ?? 'Unknown' }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ ucfirst($row->item->type ?? '-') }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ ucfirst($row->item->rarity ?? '-') }}</td>
                                        <td class="px-3 py-2 text-gray-900 font-medium">{{ $row->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    @endif
</div>
