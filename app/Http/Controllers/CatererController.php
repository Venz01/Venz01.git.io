<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Package;

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
        $userId = auth()->id();

        // categories owned by the current user (with their items)
        $categories = Category::with('items')
            ->where('user_id', $userId)     // or ->where('caterer_id', $userId) if your table uses 'caterer_id'
            ->get();

        // packages owned by the current user (with their items)
        $packages = Package::with('items')
            ->where('user_id', $userId)     // change to 'caterer_id' if needed
            ->get();

        return view('caterer.menus', compact('categories', 'packages'));
    }

    public function packages()
    {
        $categories = \App\Models\Category::with('items')
            ->where('user_id', auth()->id())
            ->get();

        return view('caterer.packages', compact('categories'));
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

