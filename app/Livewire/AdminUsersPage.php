<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminUsersPage extends Component
{
    public string $search = '';
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    public function toggleUserRole(int $userId): void
    {
        $this->resetMessages();

        $user = User::findOrFail($userId);
        $authUser = Auth::user();

        if ($authUser && (int) $authUser->id === (int) $user->id && $user->role === 'admin') {
            $this->errorMessage = 'You cannot remove your own admin role.';
            return;
        }

        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();

        $this->successMessage = 'User role updated.';
    }

    public function deleteUser(int $userId): void
    {
        $this->resetMessages();

        $user = User::findOrFail($userId);

        if ((int) $user->id === (int) Auth::id()) {
            $this->errorMessage = 'You cannot delete your own account.';
            return;
        }

        $user->delete();
        $this->successMessage = 'User deleted.';
    }

    private function resetMessages(): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    public function render(): \Illuminate\View\View
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $users = User::query()
            ->when($this->search, function ($q) {
                $search = '%' . $this->search . '%';
                $q->where(function ($q) use ($search) {
                    $q->where('username', 'like', $search)
                        ->orWhere('email', 'like', $search);
                });
            })
            ->latest()
            ->limit(100)
            ->get();

        return view('livewire.admin-users-page', compact('users'));
    }
}
