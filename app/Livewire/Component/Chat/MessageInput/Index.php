<?php

namespace App\Livewire\Component\Chat\MessageInput;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public ?int $conversationId = null;
    public string $body = '';

    public function mount(?int $conversationId = null)
    {
        $this->conversationId = $conversationId;
    }

    #[On('conversationSelected')]
    public function setConversation(int $conversationId)
    {
        $this->conversationId = $conversationId;
    }

    public function sendMessage()
    {
        if (!$this->conversationId) {
            return;
        }

        $trimmed = trim($this->body);
        if ($trimmed === '') {
            return;
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->to('/login');
        }

        $message = Message::create([
            'conversation_id' => $this->conversationId,
            'user_id' => $user->id,
            'body' => $trimmed,
        ]);

        $this->body = '';

        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Broadcast failed: ' . $e->getMessage());
        }

        $this->dispatch('messageSent');
    }

    public function appendEmoji(string $emoji)
    {
        $this->body .= $emoji;
    }

    public function render()
    {
        $conversation = $this->conversationId
            ? Conversation::with(['users'])->find($this->conversationId)
            : null;

        $user = Auth::user();
        $otherUser = $conversation?->users->firstWhere('id', '!=', $user?->id);

        return view('livewire.component.chat.message-input.index', [
            'otherUser' => $otherUser,
        ]);
    }
}
