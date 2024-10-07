<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('welcome');
    }
}
