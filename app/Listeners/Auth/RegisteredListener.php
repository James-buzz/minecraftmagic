<?php

namespace App\Listeners\Auth;

use App\Facades\Prometheus;
use Illuminate\Auth\Events\Registered;
class RegisteredListener
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $gauge = Prometheus::getOrRegisterGauge(
            config('app.name'),
            'auth_registered_total',
            'Number of registered users'
        );

        $gauge->inc();
    }
}
