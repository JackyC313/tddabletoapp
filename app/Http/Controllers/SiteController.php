<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    public function index() {
        // Send user to the dashboard if they're already logged in
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('pages.index');
    } 
}
