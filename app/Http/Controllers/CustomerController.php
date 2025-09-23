<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function home()
    {
        return view('customer.dashboard');
    }

    public function browseCaterers()
    {
        return view('customer.caterers');
    }

    public function bookings()
    {
        return view('customer.bookings');
    }

    public function cart()
    {
        return view('customer.cart');
    }

    public function payments()
    {
        return view('customer.payments');
    }

    public function notifications()
    {
        return view('customer.notifications');
    }

    public function summary()
    {
        return view('customer.summary');
    }
}

