<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display all posts (will be implemented in later phases)
     */
    public function index()
    {
        // Placeholder for post listing
        return view('posts.index');
    }

    /**
     * Show form for creating a new post
     */
    public function create()
    {
        // Placeholder for post creation form
        return view('posts.create');
    }

    /**
     * Show a specific post
     */
    public function show($id)
    {
        // Placeholder for showing individual post
        return view('posts.show', compact('id'));
    }
}