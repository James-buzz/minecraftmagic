<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    /**
     * Display the about page.
     */
    public function index(): Response
    {
        return Inertia::render('about');
    }
}
