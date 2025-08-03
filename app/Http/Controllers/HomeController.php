<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with latest news posts
     */
    public function index()
    {
        // Get the latest 5 published posts for the home page with interaction counts
        $posts = Post::with('user')
                    ->withCount(['likes', 'comments'])
                    ->published()
                    ->latest()
                    ->take(5)
                    ->get();

        return view('home', compact('posts'));
    }
}