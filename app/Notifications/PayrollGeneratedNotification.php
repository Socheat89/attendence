<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PayrollGeneratedNotification extends Notification
{
    use Queueable;

    private $payroll;

    public function __construct($payroll)
    {
        $this->payroll = $payroll;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class, 'database'];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Your Payroll is Ready 💰')
            ->icon('/images/icons/icon-192x192.png')
            ->badge('/images/icons/icon-72x72.png')
            ->body('Your payroll for ' . $this->payroll->period_start->format('M Y') . ' is available. Check your app now.')
            ->action('View Payroll', '/employee/salary');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Payroll Generated',
            'message' => 'Your payroll for ' . $this->payroll->period_start->format('M Y') . ' is available.',
            'url' => route('employee.salary.index')
        ];
    }
}
