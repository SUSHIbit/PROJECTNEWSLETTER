<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Follow or unfollow a user
     */
    public function toggle($userId)
    {
        // Make sure user is not trying to follow themselves
        if (Auth::id() == $userId) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        // Find the user to follow
        $userToFollow = User::findOrFail($userId);
        
        // Check if already following
        $existingFollow = Follow::where('follower_id', Auth::id())
                                ->where('following_id', $userId)
                                ->first();

        if ($existingFollow) {
            // Unfollow
            $existingFollow->delete();
            $message = 'You unfollowed ' . $userToFollow->display_name;
            $isFollowing = false;
        } else {
            // Follow
            Follow::create([
                'follower_id' => Auth::id(),
                'following_id' => $userId,
            ]);
            $message = 'You are now following ' . $userToFollow->display_name;
            $isFollowing = true;
        }

        // Return JSON for AJAX requests
        if (request()->ajax()) {
            return response()->json([
                'is_following' => $isFollowing,
                'message' => $message,
                'followers_count' => $userToFollow->followers()->count()
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Show followers page for a user
     */
    public function followers($userId)
    {
        $user = User::findOrFail($userId);
        
        // Get followers with pagination
        $followers = $user->followers()
                          ->orderBy('follows.created_at', 'desc')
                          ->paginate(20);

        return view('follow.followers', compact('user', 'followers'));
    }

    /**
     * Show following page for a user
     */
    public function following($userId)
    {
        $user = User::findOrFail($userId);
        
        // Get following with pagination
        $following = $user->following()
                          ->orderBy('follows.created_at', 'desc')
                          ->paginate(20);

        return view('follow.following', compact('user', 'following'));
    }

    /**
     * Show user discovery page
     */
    public function discover()
    {
        $currentUserId = Auth::id();
        
        // Get users that the current user is not following
        $suggestedUsers = User::where('id', '!=', $currentUserId)
                             ->whereNotIn('id', function($query) use ($currentUserId) {
                                 $query->select('following_id')
                                       ->from('follows')
                                       ->where('follower_id', $currentUserId);
                             })
                             ->withCount(['followers', 'publishedPosts'])
                             ->orderBy('followers_count', 'desc')
                             ->take(12)
                             ->get();

        return view('follow.discover', compact('suggestedUsers'));
    }

    /**
     * Show posts from followed users (feed)
     */
    public function feed()
    {
        $currentUserId = Auth::id();
        
        // Get posts from users the current user follows
        $posts = \App\Models\Post::whereIn('user_id', function($query) use ($currentUserId) {
                                   $query->select('following_id')
                                         ->from('follows')
                                         ->where('follower_id', $currentUserId);
                               })
                               ->with(['user'])
                               ->withCount(['likes', 'comments'])
                               ->published()
                               ->latest('published_at')
                               ->paginate(10);

        return view('follow.feed', compact('posts'));
    }
}