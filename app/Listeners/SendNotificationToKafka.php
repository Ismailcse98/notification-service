<?php

namespace App\Listeners;

use App\Events\NotificationSentEvent;
use Illuminate\Support\Facades\Log;

class SendNotificationToKafka
{
    public function handle(NotificationSentEvent $event): void
    {
        Log::info("Kafka Event Sent", [
            'notification_id' => $event->notification->id,
            'type' => $event->notification->type,
            'status' => $event->notification->status,
            'metadata' => $event->notification->metadata
        ]);
    }
}