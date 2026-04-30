<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
class LowStockNotification extends Notification
{
    protected $stock;

    public function __construct($stock)
    {
        $this->stock = $stock;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⚠ Low Stock Alert - StockProNex')

            ->greeting('Hello ' . $notifiable->name . ',')

            ->line('This is a low stock alert.')

            ->line('Product Name: ' . $this->stock->name)

            ->line('Remaining Quantity: ' . $this->stock->quantity)

            ->line('Please restock this product.')

            ->salutation('StockProNex System');
    }
}