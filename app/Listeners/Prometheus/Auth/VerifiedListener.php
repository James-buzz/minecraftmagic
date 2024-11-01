<?php

namespace App\Listeners\Prometheus\Auth;

use App\Facades\Prometheus;
use Illuminate\Auth\Events\Verified;

class VerifiedListener
{
    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $gauge = Prometheus::getOrRegisterGauge(
            'app',
            'auth_verified_total',
            'Number of email verifications',
        );

        $gauge->inc();
    }
}
