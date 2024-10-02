<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(): \Inertia\Response
    {
        return Inertia::render('dashboard');
    }
}
