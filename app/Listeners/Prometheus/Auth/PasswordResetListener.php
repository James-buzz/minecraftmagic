<?php

namespace App\Listeners\Prometheus\Auth;

use App\Facades\Prometheus;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetListener
{
    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        $authId = $event->user->getAuthIdentifier();

        $counter = Prometheus::getOrRegisterCounter(
            'app',
            'auth_password_reset_total',
            'Number of password reset attempts',
            ['auth_id'],
        );

        $counter->inc(['auth_id' => $authId]);
    }
}
