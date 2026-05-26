<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    /**
     * Display the terms and conditions.
     */
    public function terms()
    {
        return view('legal.terms');
    }

    /**
     * Display the privacy policy.
     */
    public function privacy()
    {
        return view('legal.privacy');
    }
}
