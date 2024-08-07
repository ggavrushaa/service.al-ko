<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use App\Events\WarrantyClaimApproved;
use App\Listeners\SendWarrantyClaimApprovedNotification;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {

        Event::listen(
            WarrantyClaimApproved::class,
            SendWarrantyClaimApprovedNotification::class,
        );

        Paginator::useBootstrap();
        Blade::component('components.templates.header', 'header');

        DB::listen(function ($query) {
            Log::info(
                $query->sql,
                $query->bindings,
                $query->time
            );
        });
    }
}
