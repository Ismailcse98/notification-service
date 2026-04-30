<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_id','type','status','retry_count','response_time_ms','sent_at','metadata'
    ];
    
    protected $casts = [
        'metadata' => 'array',
    ];
    

}
