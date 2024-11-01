<?php

namespace App\Listeners\Prometheus\Auth;

use App\Facades\Prometheus;
use Illuminate\Auth\Events\Registered;

class RegisteredListener
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $counter = Prometheus::getOrRegisterCounter(
            'app',
            'auth_registered_total',
            'Number of registered users'
        );

        $counter->inc();
    }
}
