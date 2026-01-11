<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Display the landing page for guest users
     */
    public function index()
    {
        // Get all caterers (users with role 'caterer')
        $caterers = User::where('role', 'caterer')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('welcome', compact('caterers'));
    }
}