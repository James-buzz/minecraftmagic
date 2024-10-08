<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PrivacyController extends Controller
{
    /**
     * Display the privacy policy.
     */
    public function index(): \Inertia\Response
    {
        return Inertia::render('legal/privacy');
    }
}
