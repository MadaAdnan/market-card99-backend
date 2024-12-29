<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendNotificationDB extends Notification
{
    use Queueable;

    public $data=[];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //dd($this->data);
        return (new MailMessage)
            ->line($this->data['title'])
            ->action('إذهب للموقع', isset($this->data['route'])?url($this->data['route']):url('/'))
            ->line($this->data['body'])
            ->line($this->data['msg']);
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->data['title'],
            'body' => $this->data['body'],
            'route' => $this->data['route'] ?? '',
            'admin'=>isset($this->data['admin'])?1:0,
            'img'=> $this->data['img'] ?? '',
            'color'=> $this->data['color'] ?? 'black'
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
