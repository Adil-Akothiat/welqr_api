<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;
    public $token;
    public $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($user, $token)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('WelQR - Reset Password Request') // Set a custom subject
            ->line('WelQR') // Replace 'WelQR' with your desired header text
            ->line('Hi, ' . $this->user->lastname . ' ' . $this->user->firstname)
            ->line('This is your reset password confirmation code.')
            ->line('Code: ' . $this->token)
            ->line('') // Optional: Add a space between the code and the footer
            ->line('Regards,') // Custom closing text
            ->line('The WelQR Team') // Custom footer text, replacing "welqr_api"
            ->line('') // Optional: Add an empty line for separation
            ->line('© 2025 WelQR. All rights reserved.'); 
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
