<?php
namespace App\DTO;

class NotificationDTO
{
    public function __construct(
        public int $user_id,
        public string $type,
        public string $recipient,
        public string $message,
        public array $metadata = []
    ) {}
}


?>