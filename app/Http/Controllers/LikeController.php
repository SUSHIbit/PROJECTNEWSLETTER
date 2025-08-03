<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Toggle like on a post
     */
    public function togglePost($postId)
    {
        $post = Post::findOrFail($postId);
        $userId = Auth::id();

        // Check if user already liked this post
        $existingLike = Like::where('user_id', $userId)
            ->where('likeable_type', 'App\Models\Post')
            ->where('likeable_id', $post->id)
            ->first();

        if ($existingLike) {
            // Unlike the post
            $existingLike->delete();
            $liked = false;
            $message = 'Post unliked!';
        } else {
            // Like the post
            Like::create([
                'user_id' => $userId,
                'likeable_type' => 'App\Models\Post',
                'likeable_id' => $post->id,
            ]);
            $liked = true;
            $message = 'Post liked!';
        }

        // Get updated like count
        $likesCount = Like::where('likeable_type', 'App\Models\Post')
            ->where('likeable_id', $post->id)
            ->count();

        // Return JSON response for AJAX requests
        if (request()->ajax()) {
            return response()->json([
                'liked' => $liked,
                'likes_count' => $likesCount,
                'message' => $message
            ]);
        }

        // Redirect back for regular requests
        return back()->with('success', $message);
    }

    /**
     * Toggle like on a comment
     */
    public function toggleComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $userId = Auth::id();

        // Check if user already liked this comment
        $existingLike = Like::where('user_id', $userId)
            ->where('likeable_type', 'App\Models\Comment')
            ->where('likeable_id', $comment->id)
            ->first();

        if ($existingLike) {
            // Unlike the comment
            $existingLike->delete();
            $liked = false;
            $message = 'Comment unliked!';
        } else {
            // Like the comment
            Like::create([
                'user_id' => $userId,
                'likeable_type' => 'App\Models\Comment',
                'likeable_id' => $comment->id,
            ]);
            $liked = true;
            $message = 'Comment liked!';
        }

        // Get updated like count
        $likesCount = Like::where('likeable_type', 'App\Models\Comment')
            ->where('likeable_id', $comment->id)
            ->count();

        // Return JSON response for AJAX requests
        if (request()->ajax()) {
            return response()->json([
                'liked' => $liked,
                'likes_count' => $likesCount,
                'message' => $message
            ]);
        }

        // Redirect back for regular requests
        return back()->with('success', $message);
    }
}