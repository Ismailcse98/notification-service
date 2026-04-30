<?php
namespace App\Repositories;
use App\Models\Notification;

class NotificationRepository
{
    public function create(array $data)
    {
        return Notification::create($data);
    }

    public function updateStatus($id, $status, $retry = null)
    {
        Notification::where('id',$id)->update([
            'status'=>$status,
            'retry_count'=>$retry
        ]);
    }
}


?>