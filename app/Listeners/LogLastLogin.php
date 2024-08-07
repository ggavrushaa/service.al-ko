<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogLastLogin
{
    public function __construct()
    {
        //
    }

    public function handle(object $event): void
    {
        $event->user->last_login_time = Carbon::now('Europe/Kiev');;
        $event->user->save();
    }
}
