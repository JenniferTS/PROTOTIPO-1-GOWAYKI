<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public function __construct(
        public string $token
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $email = $notifiable->getEmailForPasswordReset();

        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $email,
        ]);

        $broker = config('auth.defaults.passwords');
        $expireMinutes = config("auth.passwords.$broker.expire", 60);

        return (new MailMessage)
            ->subject('Restablece tu contraseña en GoWayki')
            ->view('emails.auth.reset-password', [
                'userName' => $notifiable->name ?? 'usuario',
                'resetUrl' => $resetUrl,
                'expireMinutes' => $expireMinutes,
                'supportEmail' => config('mail.from.address'),
                'appUrl' => config('app.url'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
