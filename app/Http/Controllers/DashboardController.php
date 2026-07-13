<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * The "dashboard" route is kept only as the standard post-login/verification
     * redirect target; the actual note listing (with search + filters) lives at /browse.
     */
    public function index()
    {
        return redirect()->route('browse.index');
    }
}
