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
    public string $newChatInput = '';

    public function mount(?int $activeConversationId = null)
    {
        $this->activeConversationId = $activeConversationId;
    }

    public function getListeners()
    {
        $userId = Auth::id();
        if (!$userId) {
            return [];
        }

        return [
            "echo-private:App.Models.User.{$userId},MessageSent" => 'refreshSidebar',
            "echo-private:App.Models.User.{$userId},.MessageSent" => 'refreshSidebar',
        ];
    }

    public function selectConversation(int $id)
    {
        $this->activeConversationId = $id;
        $this->dispatch('conversationSelected', conversationId: $id);
    }

    public function startNewChat()
    {
        $this->resetErrorBag();
        $identifier = trim($this->newChatInput);

        if ($identifier === '') {
            $this->addError('newChatInput', 'Please enter a username or email address.');
            return;
        }

        $user = Auth::user();
        if (!$user) return;

        // Search for user by email or name
        $targetUser = User::where('id', '!=', $user->id)
            ->where(function ($q) use ($identifier) {
                $q->where('email', $identifier)
                  ->orWhere('name', 'like', '%' . $identifier . '%');
            })
            ->first();

        if (!$targetUser) {
            $this->addError('newChatInput', 'User "' . $identifier . '" not found.');
            return;
        }

        // Check if a direct conversation already exists with this user
        $dm = Conversation::where('type', 'direct')
            ->whereHas('users', fn($q) => $q->where('users.id', $user->id))
            ->whereHas('users', fn($q) => $q->where('users.id', $targetUser->id))
            ->first();

        if (!$dm) {
            $dm = Conversation::create([
                'name' => $targetUser->name,
                'type' => 'direct',
                'avatar' => $targetUser->avatar_url,
            ]);
            $dm->users()->attach([$user->id, $targetUser->id]);
        }

        $this->newChatInput = '';
        $this->selectConversation($dm->id);
        $this->dispatch('closeNewChatModal');
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
    public function refreshSidebar($event = null)
    {
        // Dispatch toast notification for incoming message from another user
        if ($event && isset($event['user']['id']) && (int) $event['user']['id'] !== (int) Auth::id()) {
            $this->dispatch('new-message-toast',
                conversation_id: $event['conversation_id'],
                user: $event['user'],
                body: $event['body'],
                created_at_human: $event['created_at_human'] ?? 'Just now'
            );
        }

        // If receiver has no conversation selected, auto select the incoming conversation
        if ($event && isset($event['conversation_id']) && !$this->activeConversationId) {
            $this->selectConversation($event['conversation_id']);
        }
    }

    public function render()
    {
        $user = Auth::user();

        // Get 1-on-1 direct conversations for current user
        $userConversations = $user
            ? $user->conversations()
                ->where('type', 'direct')
                ->when($this->search, function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->with(['users', 'latestMessage'])
                ->get()
            : collect();

        return view('livewire.component.chat.sidebar.index', [
            'userConversations' => $userConversations,
            'user' => $user,
        ]);
    }
}
