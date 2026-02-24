<?php

namespace App\Providers;

use App\Services\Parsers\ParserFactory;
use App\Services\Parsers\ParserFactoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ParserFactoryInterface::class, ParserFactory::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
