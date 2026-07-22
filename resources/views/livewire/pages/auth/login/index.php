<?php

namespace App\Livewire\Pages\Auth\Login;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected array $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            Auth::user()->update(['is_online' => true]);

            return redirect()->intended('/chat');
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.pages.auth.login.index')
            ->layout('components.layouts.app', ['title' => 'Sign In - Chat']);
    }
}
