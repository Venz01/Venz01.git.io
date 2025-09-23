<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatererController extends Controller
{
    public function dashboard()
    {
        return view('caterer.dashboard');
    }

    public function bookings()
    {
        return view('caterer.bookings');
    }

    public function menus()
    {
        return view('caterer.menus');
    }

    public function packages()
    {
        return view('caterer.packages');
    }

    public function verifyReceipt()
    {
        return view('caterer.verifyReceipt');
    }

    public function payments()
    {
        return view('caterer.payments');
    }

    public function reviews()
    {
        return view('caterer.reviews');
    }
}

