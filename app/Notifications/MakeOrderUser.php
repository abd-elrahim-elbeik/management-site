<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class MakeOrderUser extends Notification
{
    use Queueable;

    protected $order;


    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','broadcast'];

        $channels = ['database'];

        if ($notifiable->notification_preferences['order_created']['broadcast'] ?? false) {
            $channels[] = 'broadcast';
        }

        return $channels;

    }

    public function toDatabase($notifiable)
    {
        $user = Auth::user();
        $client = $this->order->client->name;
        return[
            'body' => "A new order for $client by $user->first_name $user->last_name",
            'title' => 'new order',
            'icon' => 'fas fa-file',
            'url' => url('/dashboard'),
            'order_id' => $this->order->id,

        ];

    }

    public function toBroadcast($notifiable) //جهزنا الرسالة البرودكاست
    {
        $user = Auth::user();
        $client = $this->order->client->name;

        return new BroadcastMessage([
            'body' => "A new order for $client by $user->first_name $user->last_name",
            'title' => 'new order',
            'icon' => 'fas fa-file',
            'url' => url('/dashboard'),
            'order_id' => $this->order->id,
        ]);
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
