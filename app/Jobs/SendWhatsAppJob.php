<?php

namespace App\Jobs;

use App\Http\Controllers\cms\WhatsAppController;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $phone,
        protected string $name,
        protected string $message
    ) {}

    public function handle(): void
    {
        // Skip if the batch has been cancelled
        if ($this->batch()?->cancelled()) {
            return;
        }

        $formattedMessage = "Hello {$this->name}, {$this->message}";
        
        // Use your existing controller logic or a dedicated Service class
        WhatsAppController::sendWhatsappMessage($this->phone, $formattedMessage);
    }
}
