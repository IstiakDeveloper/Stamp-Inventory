<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginComponent extends Component
{

    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            sweetalert()->success('Logged in successfully.');
            return redirect()->route('dashboard');
        } else {
            sweetalert()->error('Invalid credentials.');
        }
    }


    public function render()
    {
        return view('livewire.login-component')->layout('layouts.guest');
    }
}
