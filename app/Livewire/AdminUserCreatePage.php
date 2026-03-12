<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Player_inventory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AdminUserCreatePage extends Component
{
    public ?int $userId = null;
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'user';
    public int $level = 1;

    public ?int $grantItemId = null;
    public int $grantQuantity = 1;

    public ?string $successMessage = null;

    public function mount(?int $userId = null): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        if (! $userId) {
            return;
        }

        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->level = (int) $user->level;
    }

    public function save(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $passwordRules = $this->userId
            ? ['nullable', 'string', 'min:8']
            : ['required', 'string', 'min:8'];

        $validated = $this->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . ($this->userId ?? 'NULL') . ',id'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . ($this->userId ?? 'NULL') . ',id'],
            'password' => $passwordRules,
            'role' => ['required', 'in:admin,user'],
            'level' => ['required', 'integer', 'min:1'],
        ]);

        if ($this->userId) {
            $user = User::findOrFail($this->userId);

            if ((int) Auth::id() === (int) $user->id && $user->role === 'admin' && $validated['role'] !== 'admin') {
                $this->addError('role', 'You cannot remove your own admin role.');
                return;
            }

            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];
            $user->level = $validated['level'];

            if (! empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();
            $this->successMessage = 'User account updated.';
            return;
        }

        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'level' => $validated['level'],
        ]);

        $this->reset(['username', 'email', 'password']);
        $this->role = 'user';
        $this->level = 1;
        $this->successMessage = 'User account created.';
    }

    public function giveItemToUser(): void
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        if (! $this->userId) {
            $this->addError('grantItemId', 'Save the user first before granting items.');
            return;
        }

        $validated = $this->validate([
            'grantItemId' => ['required', 'integer', 'exists:items,id'],
            'grantQuantity' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated) {
            $row = Player_inventory::query()
                ->where('user_id', $this->userId)
                ->where('item_id', $validated['grantItemId'])
                ->lockForUpdate()
                ->first();

            if ($row) {
                $row->increment('quantity', $validated['grantQuantity']);
                return;
            }

            Player_inventory::create([
                'user_id' => $this->userId,
                'item_id' => $validated['grantItemId'],
                'quantity' => $validated['grantQuantity'],
            ]);
        });

        $this->grantItemId = null;
        $this->grantQuantity = 1;
        $this->successMessage = 'Item granted to user.';
    }

    public function render(): \Illuminate\View\View
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $allItems = Item::query()->orderBy('name')->get();

        $inventory = collect();
        if ($this->userId) {
            $inventory = Player_inventory::query()
                ->with('item')
                ->where('user_id', $this->userId)
                ->where('quantity', '>', 0)
                ->orderByDesc('quantity')
                ->get();
        }

        return view('livewire.admin-user-create-page', compact('allItems', 'inventory'));
    }
}
