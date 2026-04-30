<?php

namespace Tests\Unit;

use App\DTO\NotificationDTO;
use PHPUnit\Framework\TestCase;

class NotificationUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    // public function test_example(): void
    // {
    //     $this->assertTrue(true);
    // }

    public function test_notification_dto()
    {
        $dto = new NotificationDTO(1,'sms','01890893098','Your bill is due',[]);

        $this->assertEquals('sms',$dto->type);
    }

}
