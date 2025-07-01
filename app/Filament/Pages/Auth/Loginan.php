<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form; // <-- Pastikan ini di-import

// 1. Pastikan Anda meng-extend dari kelas Login bawaan Filament
use Filament\Pages\Auth\Login as BaseLoginPage; 

// 2. Pastikan Anda mengimpor CaptchaField yang benar dari library
use MarcoGermani87\FilamentCaptcha\Forms\Components\CaptchaField;

class Loginan extends BaseLoginPage
{
    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    
     public function mount(): void
    {
        parent::mount();

        // dd('METHOD MOUNT() DARI CUSTOM LOGIN DIPANGGIL!');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                CaptchaField::make('captcha') // Tambahkan field captcha di sini
                    ->label('Buktikan Anda bukan robot')
                    ->required(),
            ])
            ->statePath('data');
    }
    
}