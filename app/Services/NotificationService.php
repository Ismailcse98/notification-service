<?php 
namespace App\Services;

use App\DTO\NotificationDTO;
use App\Events\NotificationSentEvent;
use App\Jobs\SendNotificationJob;
use App\Repositories\NotificationRepository;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function __construct(
        protected NotificationRepository $repo
    ) {}

    public function send(NotificationDTO $dto)
    {
        $notification = $this->repo->create((array)$dto);
        
        // For event-driven (Kafka/mock) 
        // event(new NotificationSentEvent($notification));

        // For Notificaton with Circuit Breaker
        // dispatch(new SendNotificationJob($notification->id));

        return $notification;
    }
}


?>