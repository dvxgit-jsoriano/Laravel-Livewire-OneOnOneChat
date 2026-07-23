<?php

namespace App\Livewire\Component\Chat\Sidebar;

use App\Models\Conversation;
use App\Models\Message;
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
        if ($this->activeConversationId) {
            $this->markConversationAsRead($this->activeConversationId);
        }
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
        $this->markConversationAsRead($id);
        $this->dispatch('conversationSelected', conversationId: $id);
    }

    public function markConversationAsRead(int $conversationId)
    {
        $userId = Auth::id();
        if ($userId) {
            Message::where('conversation_id', $conversationId)
                ->where('user_id', '!=', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
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
    }

    public function render()
    {
        $user = Auth::user();

        // Get 1-on-1 direct conversations for current user, sorted by latest message (sent or received)
        $userConversations = $user
            ? $user->conversations()
                ->where('type', 'direct')
                ->when($this->search, function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->with(['users', 'latestMessage'])
                ->withCount(['messages as unread_messages_count' => function ($q) use ($user) {
                    $q->where('user_id', '!=', $user->id)
                      ->whereNull('read_at');
                }])
                ->get()
                ->sortByDesc(function ($conversation) {
                    return $conversation->latestMessage?->created_at?->timestamp ?? $conversation->created_at?->timestamp ?? 0;
                })
                ->values()
            : collect();

        return view('livewire.component.chat.sidebar.index', [
            'userConversations' => $userConversations,
            'user' => $user,
        ]);
    }
}
