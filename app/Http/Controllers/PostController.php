<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display all published posts
     */
    public function index()
    {
        // Get all published posts with user information, ordered by latest first
        $posts = Post::with('user')
                    ->published()
                    ->latest()
                    ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show form for creating a new post
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a new post in the database
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'status' => 'required|in:draft,published',
        ]);

        // Handle image upload if provided
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('posts', $filename, 'public');
            $imagePath = '/storage/' . $imagePath;
        }

        // Create the post
        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'featured_image' => $imagePath,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
            'views' => 0,
        ]);

        // Redirect with success message
        if ($validated['status'] === 'published') {
            return redirect()->route('posts.show', $post->id)
                           ->with('success', 'Post published successfully!');
        } else {
            return redirect()->route('posts.index')
                           ->with('success', 'Post saved as draft successfully!');
        }
    }

    /**
     * Show a specific post
     */
    public function show($id)
    {
        // Find the post with user information
        $post = Post::with('user')->findOrFail($id);

        // Only show published posts to non-owners
        if ($post->status !== 'published' && (!Auth::check() || Auth::id() !== $post->user_id)) {
            abort(404);
        }

        // Increment view count
        $post->incrementViews();

        return view('posts.show', compact('post'));
    }

    /**
     * Show posts by the authenticated user
     */
    public function myPosts()
    {
        $posts = Post::where('user_id', Auth::id())
                    ->latest()
                    ->paginate(10);

        return view('posts.my-posts', compact('posts'));
    }

    /**
     * Show form for editing a post
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        // Check if user owns the post
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update a post
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Check if user owns the post
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        // Validate the form data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('featured_image')) {
            // Delete old image if it exists
            if ($post->featured_image) {
                $oldImagePath = str_replace('/storage/', '', $post->featured_image);
                Storage::disk('public')->delete($oldImagePath);
            }

            $image = $request->file('featured_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('posts', $filename, 'public');
            $validated['featured_image'] = '/storage/' . $imagePath;
        }

        // Update published_at if status changed to published
        if ($validated['status'] === 'published' && $post->status !== 'published') {
            $validated['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        // Update the post
        $post->update($validated);

        return redirect()->route('posts.show', $post->id)
                       ->with('success', 'Post updated successfully!');
    }

    /**
     * Delete a post
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Check if user owns the post
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        // Delete image if it exists
        if ($post->featured_image) {
            $imagePath = str_replace('/storage/', '', $post->featured_image);
            Storage::disk('public')->delete($imagePath);
        }

        $post->delete();

        return redirect()->route('posts.index')
                       ->with('success', 'Post deleted successfully!');
    }
}