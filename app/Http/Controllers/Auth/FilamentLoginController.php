<?php

namespace App\Http\Controllers\Auth;

use Filament\Http\Responses\Auth\LoginResponse;
use Filament\Pages\Auth\Login;
use Illuminate\Support\Facades\Auth;

class FilamentLoginController extends Login
{
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        if (! Auth::guard('web')->attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ], remember: false)) {
            $this->addError('email', __('filament-panels::pages/auth/login.messages.failed'));
            return null;
        }

        $user = Auth::guard('web')->user();

        if (! in_array($user->role, ['admin', 'superadmin'])) {
            Auth::guard('web')->logout();
            $this->addError('email', 'Akun ini tidak punya akses admin.');
            return null;
        }

        return app(LoginResponse::class);
    }
}