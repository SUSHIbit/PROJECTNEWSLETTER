<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Display search results
     */
    public function index(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all'); // all, posts, users, organizations
        $category = $request->get('category');
        $hashtag = $request->get('hashtag');
        
        $posts = collect();
        $users = collect();
        $organizations = collect();
        
        if ($query || $category || $hashtag) {
            // Search posts
            if (in_array($type, ['all', 'posts'])) {
                $postsQuery = Post::with(['user'])
                    ->withCount(['likes', 'comments'])
                    ->published();
                
                if ($query) {
                    $postsQuery->where(function($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('content', 'LIKE', "%{$query}%")
                          ->orWhere('category', 'LIKE', "%{$query}%");
                    });
                }
                
                if ($category) {
                    $postsQuery->where('category', $category);
                }
                
                if ($hashtag) {
                    $postsQuery->where('content', 'LIKE', "%#{$hashtag}%");
                }
                
                $posts = $postsQuery->latest()->paginate(10, ['*'], 'posts_page');
            }
            
            // Search users
            if (in_array($type, ['all', 'users']) && $query) {
                $users = User::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('username', 'LIKE', "%{$query}%")
                    ->orWhere('bio', 'LIKE', "%{$query}%")
                    ->withCount(['publishedPosts', 'followers'])
                    ->paginate(10, ['*'], 'users_page');
            }
            
            // Search organizations
            if (in_array($type, ['all', 'organizations']) && $query) {
                $organizations = Organization::where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->withCount('members')
                    ->paginate(10, ['*'], 'orgs_page');
            }
        }
        
        return view('search.index', compact('query', 'type', 'category', 'hashtag', 'posts', 'users', 'organizations'));
    }
    
    /**
     * Show trending posts
     */
    public function trending()
    {
        // Get posts from last 7 days, sorted by likes + comments + views
        $posts = Post::with(['user'])
            ->withCount(['likes', 'comments'])
            ->published()
            ->where('published_at', '>=', now()->subDays(7))
            ->get()
            ->sortByDesc(function($post) {
                return ($post->likes_count * 3) + ($post->comments_count * 2) + ($post->views * 0.1);
            })
            ->take(20);
            
        return view('search.trending', compact('posts'));
    }
    
    /**
     * Show explore page with recommendations
     */
    public function explore()
    {
        // Featured posts (high engagement)
        $featuredPosts = Post::with(['user'])
            ->withCount(['likes', 'comments'])
            ->published()
            ->where('published_at', '>=', now()->subDays(30))
            ->get()
            ->sortByDesc(function($post) {
                return ($post->likes_count * 2) + $post->comments_count + ($post->views * 0.05);
            })
            ->take(6);
        
        // Recent posts
        $recentPosts = Post::with(['user'])
            ->withCount(['likes', 'comments'])
            ->published()
            ->latest()
            ->take(6)
            ->get();
        
        // Popular categories
        $popularCategories = Post::published()
            ->whereNotNull('category')
            ->where('published_at', '>=', now()->subDays(30))
            ->select('category', DB::raw('COUNT(*) as post_count'))
            ->groupBy('category')
            ->orderBy('post_count', 'desc')
            ->take(8)
            ->get();
        
        // Active users (those who posted recently)
        $activeUsers = User::whereHas('posts', function($query) {
                $query->published()->where('published_at', '>=', now()->subDays(7));
            })
            ->withCount(['publishedPosts', 'followers'])
            ->orderBy('published_posts_count', 'desc')
            ->take(8)
            ->get();
        
        // Popular hashtags (extract from post content)
        $popularHashtags = $this->extractPopularHashtags();
        
        return view('search.explore', compact(
            'featuredPosts', 
            'recentPosts', 
            'popularCategories', 
            'activeUsers',
            'popularHashtags'
        ));
    }
    
    /**
     * Get search suggestions for autocomplete
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $suggestions = [];
        
        // Add user suggestions
        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('username', 'LIKE', "%{$query}%")
            ->take(3)
            ->get(['id', 'name', 'username']);
        
        foreach ($users as $user) {
            $suggestions[] = [
                'type' => 'user',
                'text' => $user->display_name,
                'url' => route('profile.show', $user->username ?: $user->id)
            ];
        }
        
        // Add post title suggestions
        $posts = Post::where('title', 'LIKE', "%{$query}%")
            ->published()
            ->take(3)
            ->get(['id', 'title']);
        
        foreach ($posts as $post) {
            $suggestions[] = [
                'type' => 'post',
                'text' => $post->title,
                'url' => route('posts.show', $post->id)
            ];
        }
        
        // Add category suggestions
        $categories = ['technology', 'politics', 'sports', 'health', 'science', 'business', 'entertainment'];
        $matchingCategories = array_filter($categories, function($category) use ($query) {
            return stripos($category, $query) !== false;
        });
        
        foreach (array_slice($matchingCategories, 0, 2) as $category) {
            $suggestions[] = [
                'type' => 'category',
                'text' => ucfirst($category),
                'url' => route('search.index', ['category' => $category])
            ];
        }
        
        return response()->json($suggestions);
    }
    
    /**
     * Extract popular hashtags from post content
     */
    private function extractPopularHashtags()
    {
        $posts = Post::published()
            ->where('published_at', '>=', now()->subDays(30))
            ->pluck('content');
        
        $hashtags = [];
        
        foreach ($posts as $content) {
            preg_match_all('/#([a-zA-Z0-9_]+)/', $content, $matches);
            foreach ($matches[1] as $hashtag) {
                $hashtag = strtolower($hashtag);
                if (isset($hashtags[$hashtag])) {
                    $hashtags[$hashtag]++;
                } else {
                    $hashtags[$hashtag] = 1;
                }
            }
        }
        
        arsort($hashtags);
        return array_slice($hashtags, 0, 10, true);
    }
}