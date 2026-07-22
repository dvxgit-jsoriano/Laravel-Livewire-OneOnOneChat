<?php

namespace App\Livewire\Component\Chat\MessageFeed;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public ?int $conversationId = null;

    public function mount(?int $conversationId = null)
    {
        $this->conversationId = $conversationId;
    }

    #[On('conversationSelected')]
    public function setConversation(int $conversationId)
    {
        $this->conversationId = $conversationId;
    }

    #[On('messageSent')]
    public function handleNewMessage()
    {
    }

    public function getListeners()
    {
        $userId = Auth::id();
        $listeners = [];

        if ($this->conversationId) {
            $listeners["echo-private:chat.{$this->conversationId},MessageSent"] = 'handleNewMessage';
            $listeners["echo-private:chat.{$this->conversationId},.MessageSent"] = 'handleNewMessage';
        }

        if ($userId) {
            $listeners["echo-private:App.Models.User.{$userId},MessageSent"] = 'handleNewMessage';
            $listeners["echo-private:App.Models.User.{$userId},.MessageSent"] = 'handleNewMessage';
        }

        return $listeners;
    }

    public function render()
    {
        $conversation = $this->conversationId
            ? Conversation::with(['users'])->find($this->conversationId)
            : null;

        $messages = $this->conversationId
            ? Message::where('conversation_id', $this->conversationId)
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get()
            : collect();

        return view('livewire.component.chat.message-feed.index', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }
}
