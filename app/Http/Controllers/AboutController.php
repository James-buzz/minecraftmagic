<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class AboutController extends Controller
{
    /**
     * Display the about page.
     */
    public function index(): \Inertia\Response
    {
        return Inertia::render('about');
    }
}
