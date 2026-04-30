<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','type','recipient','message','metadata','status','retry_count'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
