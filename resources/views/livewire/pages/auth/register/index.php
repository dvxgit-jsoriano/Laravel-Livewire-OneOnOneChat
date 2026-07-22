<?php

namespace App\Livewire\Pages\Auth\Register;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Index extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected array $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($this->name),
            'is_online' => true,
        ]);

        // Attach user to main-chat channel
        $channel = Conversation::where('name', 'main-chat')->first();
        if ($channel) {
            $channel->users()->attach($user->id);
        }

        Auth::login($user);

        return redirect()->to('/chat');
    }

    public function render()
    {
        return view('livewire.pages.auth.register.index')
            ->layout('components.layouts.app', ['title' => 'Register - Chat']);
    }
}
