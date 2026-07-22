<?php

namespace App\Livewire\Pages\Chat\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public int $activeConversationId = 1;

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->to('/login');
        }
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
