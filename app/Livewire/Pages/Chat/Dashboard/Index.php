<?php

namespace App\Livewire\Pages\Chat\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public ?int $activeConversationId = null;

    public function mount()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->to('/login');
        }

        // Auto-select the first conversation if the user already has one
        $firstDm = $user->conversations()->where('type', 'direct')->first();
        $this->activeConversationId = $firstDm?->id;
    }

    #[On('conversationSelected')]
    public function updateActiveConversation(int $conversationId)
    {
        $this->activeConversationId = $conversationId;
    }

    public function render()
    {
        return view('livewire.pages.chat.dashboard.index')
            ->layout('components.layouts.app', ['title' => 'Dashboard - Chat']);
    }
}
