<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PrivacyController extends Controller
{
    /**
     * Display the privacy policy.
     */
    public function index(): Response
    {
        return Inertia::render('legal/privacy');
    }
}
