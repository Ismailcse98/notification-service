<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\NotificationLog;
use App\Services\CircuitBreakerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    public function __construct(public int $notificationId) {}

    public function handle(): void
    {
        $start = microtime(true);

        Log::info('Job started', [
            'notification_id' => $this->notificationId
        ]);

        $notification = Notification::find($this->notificationId);

        if (!$notification) {
            Log::error('Notification not found', [
                'notification_id' => $this->notificationId
            ]);
            return;
        }

        // Circuit Breaker instance
        $breaker = app(CircuitBreakerService::class);

        // Service key
        $key = $notification->type . '_gateway';

        // check circuit open
        if ($breaker->isOpen($key)) {
            Log::warning('Circuit Open - Skipping job', [
                'service' => $key
            ]);

            
            $notification->update(['status' => 'failed']);

            return;
        }

        try {
            //  mark processing
            $notification->update([
                'status' => 'processing'
            ]);

            // external API call
            sleep(1);

            // random failure (for testing)
            // if (rand(1,10) <= 3) {
            //     throw new \Exception("Gateway failure");
            // }

            // success
            $notification->update([
                'status' => 'sent'
            ]);

            // success → reset circuit breaker
            $breaker->recordSuccess($key);

            $notification->refresh();

            $metadata = is_array($notification->metadata)
                ? $notification->metadata
                : json_decode($notification->metadata, true);

            // AI log
            NotificationLog::create([
                'notification_id'   => $notification->id,
                'type'              => $notification->type,
                'status'            => $notification->status,
                'retry_count'       => $notification->retry_count,
                'response_time_ms'  => round((microtime(true) - $start) * 1000, 2),
                'sent_at'           => now(),
                'metadata'          => $metadata
            ]);

            Log::info('Notification processed successfully', [
                'notification_id' => $notification->id
            ]);

        } catch (Throwable $e) {

            // increase retry count
            $notification->increment('retry_count');

            // failure in circuit breaker
            $breaker->recordFailure($key);

            Log::error('Notification job failed', [
                'notification_id' => $this->notificationId,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            if ($this->attempts() >= $this->tries) {
                $notification->update([
                    'status' => 'failed'
                ]);
            }

            throw $e; // retry trigger
        }
    }
}