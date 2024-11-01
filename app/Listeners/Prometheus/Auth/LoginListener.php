<?php

namespace App\Listeners\Prometheus\Auth;

use App\Facades\Prometheus;
use Illuminate\Auth\Events\Login;

class LoginListener
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $counter = Prometheus::getOrRegisterCounter(
            'app',
            'auth_login_total',
            'Number of logins',
        );

        $counter->inc();
    }
}
