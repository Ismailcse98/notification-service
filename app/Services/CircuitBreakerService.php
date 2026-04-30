<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CircuitBreakerService
{
   
    public function isOpen(string $key): bool
    {
        return Cache::get($key . ':open', false);
    }

    public function recordSuccess(string $key): void
    {
        Cache::forget($key . ':failures');
        Cache::forget($key . ':open');
    }

  
    public function recordFailure(string $key, int $threshold = 5): void
    {
        
        $failures = Cache::increment($key . ':failures');

        
        Cache::put($key . ':last_failure', now(), 300);

        
        if ($failures >= $threshold) {
            Cache::put($key . ':open', true, now()->addMinute());
        }
    }


    public function reset(string $key): void
    {
        Cache::forget($key . ':failures');
        Cache::forget($key . ':open');
        Cache::forget($key . ':last_failure');
    }
}