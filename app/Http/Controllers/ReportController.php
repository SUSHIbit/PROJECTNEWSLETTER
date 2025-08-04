<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Check if user is authenticated
     */
    private function checkAuth()
    {
        if (!Auth::check()) {
            abort(403, 'You must be logged in to access this page.');
        }
    }

    /**
     * Show report form for a post
     */
    public function reportPost($id)
    {
        $this->checkAuth();
        
        $post = Post::findOrFail($id);
        
        // Check if user already reported this post
        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('reportable_type', 'App\Models\Post')
            ->where('reportable_id', $id)
            ->first();

        if ($existingReport) {
            return back()->with('error', 'You have already reported this post.');
        }

        return view('reports.create', compact('post'))->with('reportable_type', 'post');
    }

    /**
     * Show report form for a comment
     */
    public function reportComment($id)
    {
        $this->checkAuth();
        
        $comment = Comment::with('post')->findOrFail($id);
        
        // Check if user already reported this comment
        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('reportable_type', 'App\Models\Comment')
            ->where('reportable_id', $id)
            ->first();

        if ($existingReport) {
            return back()->with('error', 'You have already reported this comment.');
        }

        return view('reports.create', compact('comment'))->with('reportable_type', 'comment');
    }

    /**
     * Show report form for a user
     */
    public function reportUser($id)
    {
        $this->checkAuth();
        
        $user = User::findOrFail($id);
        
        // Check if user trying to report themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot report yourself.');
        }
        
        // Check if user already reported this user
        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('reportable_type', 'App\Models\User')
            ->where('reportable_id', $id)
            ->first();

        if ($existingReport) {
            return back()->with('error', 'You have already reported this user.');
        }

        return view('reports.create', compact('user'))->with('reportable_type', 'user');
    }

    /**
     * Store a new report
     */
    public function store(Request $request)
    {
        $this->checkAuth();
        
        $request->validate([
            'reportable_type' => 'required|in:App\Models\Post,App\Models\Comment,App\Models\User',
            'reportable_id' => 'required|integer',
            'reason' => 'required|in:spam,inappropriate,harassment,fake_news,copyright,other',
            'description' => 'nullable|string|max:1000',
        ]);

        // Check if the reportable item exists
        $reportableClass = $request->reportable_type;
        $reportable = $reportableClass::findOrFail($request->reportable_id);

        // Check if user already reported this item
        $existingReport = Report::where('reporter_id', Auth::id())
            ->where('reportable_type', $request->reportable_type)
            ->where('reportable_id', $request->reportable_id)
            ->first();

        if ($existingReport) {
            return back()->with('error', 'You have already reported this item.');
        }

        // Create the report
        Report::create([
            'reporter_id' => Auth::id(),
            'reportable_type' => $request->reportable_type,
            'reportable_id' => $request->reportable_id,
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Thank you for your report. Our team will review it shortly.');
    }

    /**
     * Show user's reports
     */
    public function myReports()
    {
        $this->checkAuth();
        
        $reports = Report::where('reporter_id', Auth::id())
            ->with(['reportable'])
            ->latest()
            ->paginate(10);

        return view('reports.my-reports', compact('reports'));
    }
}