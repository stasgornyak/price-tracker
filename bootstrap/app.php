<?php

use App\Services\Subscription\CheckAllPrices;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withSchedule(function (Schedule $schedule) {
        $checkIntervalInMinutes = config('subscriptions.check_interval_in_minutes');

        $schedule->call(new CheckAllPrices)->everyMinute();

        $schedule->call(new CheckAllPrices)
            ->everyMinute()
            ->when(function () use ($checkIntervalInMinutes) {
                $lastRun = Cache::get('last_run_process_subscriptions');
                if (! $lastRun || now()->diffInMinutes($lastRun) >= $checkIntervalInMinutes) {
                    Cache::put('last_run_process_subscriptions', now(), now()->addMinutes(110));

                    return true;
                }

                return false;
            });
    })
    ->create();
