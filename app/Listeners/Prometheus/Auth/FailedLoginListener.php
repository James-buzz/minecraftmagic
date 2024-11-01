<?php

namespace App\Listeners\Prometheus\Auth;

use App\Facades\Prometheus;
use Illuminate\Auth\Events\Failed;

class FailedLoginListener
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'];

        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'auth_failed_login_total',
            'Number of failed login attempts',
            ['subject'],
        );

        $gauge->inc(['subject' => $email]);
    }
}
