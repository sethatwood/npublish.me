<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

public function boot()
{
    RateLimiter::for('submissions', function ($request) {
        return Limit::perMinute(5)->by($request->ip());
    });
}
