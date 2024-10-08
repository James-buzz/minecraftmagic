<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class TermsController extends Controller
{
    /**
     * Display the terms of service.
     */
    public function index(): \Inertia\Response
    {
        return Inertia::render('legal/terms');
    }
}
