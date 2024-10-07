<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class TermsController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('legal/terms');
    }
}
