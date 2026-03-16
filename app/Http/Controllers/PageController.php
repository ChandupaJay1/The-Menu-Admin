<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return view('onboarding');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function categories()
    {
        return view('categories');
    }

    public function category($slug)
    {
        return view('category-menu', compact('slug'));
    }

    public function bills()
    {
        return view('bills');
    }

    public function checkoutSettings()
    {
        return view('settings.checkout');
    }

    public function securitySettings()
    {
        return view('settings.security');
    }
}
