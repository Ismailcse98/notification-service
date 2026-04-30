<?php

namespace App\Http\Controllers\Api\V1;

use App\DTO\NotificationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendNotificationRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function send(SendNotificationRequest $request)
    {
        $dto = new NotificationDTO(...$request->validated());

        $notification = app(NotificationService::class)->send($dto);

        return response()->json([
            'id'=>$notification->id,
            'status'=>'queued'
        ]);
    }
}
