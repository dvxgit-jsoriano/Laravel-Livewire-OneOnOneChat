<?php

namespace App\Livewire\Component\Chat\Sidebar;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public ?int $activeConversationId = null;
    public string $search = '';

    public function selectConversation(int $id)
    {
        $this->activeConversationId = $id;
        $this->dispatch('conversationSelected', conversationId: $id);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->update(['is_online' => false]);
            Auth::logout();
        }

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/login');
    }

    #[On('messageSent')]
    public function refreshSidebar()
    {
        // Re-render sidebar when new message arrives to update last message time
    }

    public function render()
    {
        $user = Auth::user();

        // Fetch channels
        $channels = Conversation::where('type', 'channel')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->with(['latestMessage'])
            ->get();

        // Fetch contacts / direct messages
        $contacts = User::where('id', '!=', $user?->id)
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->get();

        return view('livewire.component.chat.sidebar.index', [
            'channels' => $channels,
            'contacts' => $contacts,
            'user' => $user,
        ]);
    }
}
