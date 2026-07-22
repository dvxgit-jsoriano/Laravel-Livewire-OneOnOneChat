<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->message->load(['user', 'conversation.users']);
    }

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('chat.' . $this->message->conversation_id),
        ];

        // Also broadcast to the recipient users' personal private channels so they receive the new chat/message instantly
        if ($this->message->conversation) {
            $recipientIds = $this->message->conversation->users
                ->pluck('id')
                ->reject(fn($id) => (int) $id === (int) $this->message->user_id);

            foreach ($recipientIds as $recipientId) {
                $channels[] = new PrivateChannel('App.Models.User.' . $recipientId);
            }
        }

        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'body' => $this->message->body,
            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
                'avatar' => $this->message->user->avatar_url,
                'is_online' => (bool) $this->message->user->is_online,
            ],
            'created_at_human' => $this->message->created_at->format('g:i A'),
            'created_at_day' => $this->message->created_at->isToday() ? 'Today' : $this->message->created_at->format('M d'),
        ];
    }
}
