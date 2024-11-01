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
        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'auth_login_total',
            'Number of logins',
        );

        $gauge->inc();
    }
}
