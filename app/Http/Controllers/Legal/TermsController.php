<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class TermsController extends Controller
{
    /**
     * Display the terms of service.
     */
    public function index(): Response
    {
        return Inertia::render('Legal/Terms');
    }
}
