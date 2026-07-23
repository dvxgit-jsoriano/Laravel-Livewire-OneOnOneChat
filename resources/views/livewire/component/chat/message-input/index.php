<?php

namespace App\Livewire\Component\Chat\MessageInput;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

use Livewire\Component;

class Index extends Component
{
    public int $conversationId = 1;
    public string $body = '';

    #[On('conversationSelected')]
    public function setConversation(int $conversationId)
    {
        $this->conversationId = $conversationId;
    }

    public function sendMessage()
    {
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

        // Broadcast real-time event via Reverb
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
        return view('livewire.component.chat.message-input.index');
    }
}
