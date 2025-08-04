<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Report;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Check if user is admin for all admin methods
     */
    private function checkAdmin()
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Access denied. Admin privileges required.');
        }
    }

    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $this->checkAdmin();
        
        // Get statistics
        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'total_comments' => Comment::count(),
            'total_organizations' => Organization::count(),
            'pending_reports' => Report::pending()->count(),
            'new_users_this_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            'new_posts_this_week' => Post::where('created_at', '>=', now()->subWeek())->count(),
        ];

        // Get recent activity
        $recent_users = User::latest()->take(5)->get();
        $recent_posts = Post::with('user')->latest()->take(5)->get();
        $pending_reports = Report::with(['reporter', 'reportable'])->pending()->latest()->take(5)->get();

        // Get popular posts (by views and interactions)
        $popular_posts = Post::published()
            ->withCount(['likes', 'comments'])
            ->orderByDesc('views')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_posts', 'pending_reports', 'popular_posts'));
    }

    /**
     * User management
     */
    public function users(Request $request)
    {
        $this->checkAdmin();
        
        $query = User::withCount(['posts', 'comments', 'followers', 'following']);

        // Filter by search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%")
                  ->orWhere('username', 'LIKE', "%{$request->search}%");
            });
        }

        // Filter by account type
        if ($request->account_type) {
            $query->where('account_type', $request->account_type);
        }

        // Filter by admin status
        if ($request->has('is_admin')) {
            $query->where('is_admin', $request->is_admin);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details
     */
    public function showUser($id)
    {
        $this->checkAdmin();
        
        $user = User::withCount(['posts', 'comments', 'followers', 'following'])->findOrFail($id);
        $recent_posts = $user->posts()->latest()->take(5)->get();
        $recent_comments = $user->comments()->with('post')->latest()->take(5)->get();

        return view('admin.users.show', compact('user', 'recent_posts', 'recent_comments'));
    }

    /**
     * Toggle admin status
     */
    public function toggleAdmin($id)
    {
        $this->checkAdmin();
        
        $user = User::findOrFail($id);
        
        // Prevent removing admin from yourself
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot remove admin privileges from yourself.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $status = $user->is_admin ? 'granted' : 'removed';
        return back()->with('success', "Admin privileges {$status} for {$user->name}.");
    }

    /**
     * Suspend/unsuspend user
     */
    public function toggleUserStatus($id)
    {
        $this->checkAdmin();
        
        $user = User::findOrFail($id);
        
        // For simplicity, we'll use email_verified_at to suspend users
        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $message = "User {$user->name} has been suspended.";
        } else {
            $user->update(['email_verified_at' => now()]);
            $message = "User {$user->name} has been unsuspended.";
        }

        return back()->with('success', $message);
    }

    /**
     * Post management
     */
    public function posts(Request $request)
    {
        $this->checkAdmin();
        
        $query = Post::with(['user'])->withCount(['likes', 'comments']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'LIKE', "%{$request->search}%")
                  ->orWhere('content', 'LIKE', "%{$request->search}%");
            });
        }

        $posts = $query->latest()->paginate(20);

        // Get categories for filter
        $categories = Post::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category')
            ->sort();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show post details
     */
    public function showPost($id)
    {
        $this->checkAdmin();
        
        $post = Post::with(['user', 'comments.user'])->withCount(['likes', 'comments'])->findOrFail($id);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Delete post
     */
    public function deletePost($id)
    {
        $this->checkAdmin();
        
        $post = Post::findOrFail($id);
        $title = $post->title;
        
        // Delete associated image if exists
        if ($post->featured_image) {
            $imagePath = str_replace('/storage/', '', $post->featured_image);
            Storage::disk('public')->delete($imagePath);
        }
        
        $post->delete();

        return redirect()->route('admin.posts')->with('success', "Post '{$title}' has been deleted.");
    }

    /**
     * Reports management
     */
    public function reports(Request $request)
    {
        $this->checkAdmin();
        
        $query = Report::with(['reporter', 'reportable', 'reviewer']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by reason
        if ($request->reason) {
            $query->where('reason', $request->reason);
        }

        $reports = $query->latest()->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Show report details
     */
    public function showReport($id)
    {
        $this->checkAdmin();
        
        $report = Report::with(['reporter', 'reportable', 'reviewer'])->findOrFail($id);
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Update report status
     */
    public function updateReport(Request $request, $id)
    {
        $this->checkAdmin();
        
        $request->validate([
            'status' => 'required|in:reviewed,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report = Report::findOrFail($id);
        
        switch ($request->status) {
            case 'reviewed':
                $report->markAsReviewed(Auth::id(), $request->admin_notes);
                break;
            case 'resolved':
                $report->markAsResolved(Auth::id(), $request->admin_notes);
                break;
            case 'dismissed':
                $report->markAsDismissed(Auth::id(), $request->admin_notes);
                break;
        }

        return back()->with('success', 'Report status updated successfully.');
    }

    /**
     * Analytics page
     */
    public function analytics()
    {
        $this->checkAdmin();
        
        // User statistics
        $user_stats = [
            'total_users' => User::count(),
            'personal_accounts' => User::where('account_type', 'personal')->count(),
            'organization_accounts' => User::where('account_type', 'organization')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'users_this_month' => User::where('created_at', '>=', now()->subMonth())->count(),
            'active_users' => User::where('last_active_at', '>=', now()->subWeek())->count(),
        ];

        // Content statistics
        $content_stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'posts_this_month' => Post::where('created_at', '>=', now()->subMonth())->count(),
            'total_comments' => Comment::count(),
            'comments_this_month' => Comment::where('created_at', '>=', now()->subMonth())->count(),
        ];

        // Popular categories
        $popular_categories = Post::select('category', DB::raw('COUNT(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        // Top authors
        $top_authors = User::withCount('publishedPosts')
            ->having('published_posts_count', '>', 0)
            ->orderByDesc('published_posts_count')
            ->take(10)
            ->get();

        // Daily registrations for the last 30 days
        $daily_registrations = User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.analytics', compact(
            'user_stats', 
            'content_stats', 
            'popular_categories', 
            'top_authors', 
            'daily_registrations'
        ));
    }

    /**
     * System settings
     */
    public function settings()
    {
        $this->checkAdmin();
        
        return view('admin.settings');
    }
}