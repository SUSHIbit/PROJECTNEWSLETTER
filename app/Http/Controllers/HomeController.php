<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     * This will show the main feed of news posts
     */
    public function index()
    {
        // For now, just return the home view
        // Later we'll fetch posts from database
        return view('home');
    }
}