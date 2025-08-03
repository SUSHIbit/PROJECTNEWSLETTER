<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a new comment
     */
    public function store(Request $request, $postId)
    {
        // Find the post
        $post = Post::findOrFail($postId);
        
        // Validate the comment
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        // Create the comment using validated data
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
        ]);

        // Load the user relationship for the response
        $comment->load('user');

        // Redirect back with success message
        return back()->with('success', 'Comment posted successfully!');
    }

    /**
     * Delete a comment
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // Check if user owns the comment
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'You can only delete your own comments.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}