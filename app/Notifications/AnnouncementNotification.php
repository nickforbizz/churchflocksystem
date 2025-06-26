<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
// use NotificationChannels\Twilio\TwilioSmsMessage; // Uncomment if using Twilio for SMS

class AnnouncementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $announcement;
    protected $message;
    protected $channelType; // 'email' or 'sms'

    /**
     * Create a new notification instance.
     *
     * @param Announcement $announcement
     * @param string $channelType
     * @return void
     */
    public function __construct(Announcement $announcement, string $channelType, string $message = '')
    {
        $this->announcement = $announcement;
        $this->channelType = $channelType;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->channelType === 'email') {
            return ['mail'];
        } elseif ($this->channelType === 'sms') {
            // To send SMS, you need to install and configure a package like NotificationChannels/Twilio
            // For example: composer require laravel/nexmo-notification-channel or composer require laravel/twilio-notification-channel
            // Then configure the service in config/services.php and add 'phone_number' to your User model.
            // return ['twilio']; // Example for Twilio
            return []; // Return empty if SMS channel is not configured
        }
        return [];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject('Announcement: ' . $this->announcement->title) // Changed subject for clarity
            ->greeting('Hello ' . ($notifiable->full_name ?? $notifiable->name) . ','); // Use full_name for Member, fallback to name for User

        if ($this->message) { // Use the message property from the notification instance
            foreach (explode("\n", $this->message) as $line) { // Split custom message by new lines
                $mailMessage->line($line);
            }
        } else {
            $mailMessage->line('A new announcement has been made:');
            $mailMessage->line('**' . $this->announcement->title . '**');
            $mailMessage->line($this->announcement->description ?? $this->announcement->body);
        }

        $mailMessage->action('View Details', url('/announcements/' . $this->announcement->id))
            ->line('Thank you for being a part of our community!');

        // Log::info('Sending announcement email to: ' . $notifiable->email); // Uncomment for debugging
        // Log::info('Email content: ' . $mailMessage->render()); // Uncomment for debugging

        return $mailMessage;
    }

    /**
     * Get the SMS representation of the notification.
     * Uncomment and modify if you have an SMS notification channel configured.
     *
     * @param mixed $notifiable
     * @return \NotificationChannels\Twilio\TwilioSmsMessage
     */
    // public function toTwilio($notifiable)
    // {
    //     $content = $this->message ?? "New Announcement: " . $this->announcement->title;
    //     $content .= ". View: " . url('/announcements/' . $this->announcement->id); // Use correct URL
    //
    //     return (new TwilioSmsMessage())
    //         ->content($content);
    // }
}
