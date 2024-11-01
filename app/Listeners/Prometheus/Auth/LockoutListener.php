<?php

namespace App\Listeners\Prometheus\Auth;

use App\Facades\Prometheus;
use Illuminate\Auth\Events\Lockout;

class LockoutListener
{
    /**
     * Handle the event.
     */
    public function handle(Lockout $event): void
    {
        $ip = $event->request->ip();

        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'auth_lockout_total',
            'Number of lockouts',
            ['ip'],
        );

        $gauge->inc(['ip' => $ip]);
    }
}
