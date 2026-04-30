<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{
    public function trainingData()
    {
        return NotificationLog::query()
            ->select([
                'notification_id',
                'type',
                'status',
                'retry_count',
                'response_time_ms',
                'sent_at',
                'metadata'
            ])
            ->latest('id')
            ->cursorPaginate(1000);
    }

    public function dashboard()
    {
        return Cache::remember('dashboard_stats', 60, function () {
            return Notification::query()
                ->selectRaw("
                    COUNT(*) as total,
                    SUM(status = 'pending') as pending,
                    SUM(status = 'processing') as processing,
                    SUM(status = 'sent') as sent,
                    SUM(status = 'failed') as failed
                ")
                ->first();
        });
    }
}
