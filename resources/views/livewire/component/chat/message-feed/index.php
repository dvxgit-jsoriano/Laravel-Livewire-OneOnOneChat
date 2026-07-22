<?php

namespace App\Livewire\Component\Chat\MessageFeed;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public int $conversationId = 1;

    #[On('conversationSelected')]
    public function setConversation(int $conversationId)
    {
        $this->conversationId = $conversationId;
    }

    #[On('messageSent')]
    public function handleNewMessage()
    {
        // Re-render when message sent locally or via Reverb
    }

    public function getListeners()
    {
        return [
            "echo-private:chat.{$this->conversationId},MessageSent" => 'handleNewMessage',
            "echo-private:chat.{$this->conversationId},.MessageSent" => 'handleNewMessage',
        ];
    }

    public function render()
    {
        $conversation = Conversation::with(['users'])->find($this->conversationId)
            ?? Conversation::first();

        $messages = Message::where('conversation_id', $this->conversationId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.component.chat.message-feed.index', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }
}
