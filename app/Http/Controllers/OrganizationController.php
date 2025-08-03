<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Models\OrganizationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    /**
     * Display a listing of organizations
     */
    public function index()
    {
        $organizations = Organization::with(['owner', 'memberships'])
                                   ->withCount('members')
                                   ->latest()
                                   ->paginate(12);

        return view('organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new organization
     */
    public function create()
    {
        return view('organizations.create');
    }

    /**
     * Store a newly created organization
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // Handle logo upload if provided
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = time() . '_' . $image->getClientOriginalName();
            $logoPath = $image->storeAs('organizations/logos', $filename, 'public');
            $logoPath = '/storage/' . $logoPath;
        }

        // Create the organization
        $organization = Organization::create([
            'owner_id' => Auth::id(),
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'website' => $validated['website'],
            'email' => $validated['email'],
            'logo' => $logoPath,
        ]);

        // Add the owner as an admin member
        $organization->addMember(Auth::user(), 'admin');

        return redirect()->route('organizations.show', $organization->slug)
                       ->with('success', 'Organization created successfully!');
    }

    /**
     * Display the specified organization
     */
    public function show($slug)
    {
        $organization = Organization::where('slug', $slug)
                                  ->with(['owner', 'members', 'memberships'])
                                  ->withCount('members')
                                  ->firstOrFail();

        // Get organization posts
        $posts = \App\Models\Post::whereIn('user_id', $organization->members->pluck('id'))
                                ->with(['user'])
                                ->withCount(['likes', 'comments'])
                                ->published()
                                ->latest()
                                ->take(6)
                                ->get();

        return view('organizations.show', compact('organization', 'posts'));
    }

    /**
     * Show the form for editing the organization
     */
    public function edit($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        // Check if user can edit this organization
        if (!$organization->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this organization.');
        }

        return view('organizations.edit', compact('organization'));
    }

    /**
     * Update the specified organization
     */
    public function update(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        // Check if user can edit this organization
        if (!$organization->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to edit this organization.');
        }

        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists (simple way)
            if ($organization->logo && file_exists(public_path(str_replace('/storage/', 'storage/', $organization->logo)))) {
                unlink(public_path(str_replace('/storage/', 'storage/', $organization->logo)));
            }

            $image = $request->file('logo');
            $filename = time() . '_' . $image->getClientOriginalName();
            $logoPath = $image->storeAs('organizations/logos', $filename, 'public');
            $validated['logo'] = '/storage/' . $logoPath;
        }

        // Update slug if name changed
        if ($validated['name'] !== $organization->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Update the organization
        $organization->update($validated);

        return redirect()->route('organizations.show', $organization->slug)
                       ->with('success', 'Organization updated successfully!');
    }

    /**
     * Show organization members
     */
    public function members($slug)
    {
        $organization = Organization::where('slug', $slug)
                                  ->with(['owner', 'memberships.user'])
                                  ->firstOrFail();

        return view('organizations.members', compact('organization'));
    }

    /**
     * Show form to invite members
     */
    public function inviteForm($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        // Check if user can manage members
        if (!$organization->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to invite members.');
        }

        return view('organizations.invite', compact('organization'));
    }

    /**
     * Invite a member to the organization
     */
    public function inviteMember(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        // Check if user can manage members
        if (!$organization->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to invite members.');
        }

        // Validate the form data
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:admin,editor,member',
        ]);

        // Find the user by email
        $userToInvite = User::where('email', $validated['email'])->first();

        // Check if user is already a member
        if ($organization->hasMember($userToInvite)) {
            return back()->with('error', 'User is already a member of this organization.');
        }

        // Add the member
        $organization->addMember($userToInvite, $validated['role']);

        return redirect()->route('organizations.members', $organization->slug)
                       ->with('success', 'Member invited successfully!');
    }

    /**
     * Update member role
     */
    public function updateMemberRole(Request $request, $slug, $userId)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        // Check if user can manage members
        if (!$organization->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to manage members.');
        }

        // Validate the request
        $validated = $request->validate([
            'role' => 'required|in:admin,editor,member',
        ]);

        // Find the membership
        $membership = OrganizationMember::where('organization_id', $organization->id)
                                       ->where('user_id', $userId)
                                       ->firstOrFail();

        // Update the role
        $membership->update(['role' => $validated['role']]);

        return back()->with('success', 'Member role updated successfully!');
    }

    /**
     * Remove a member from the organization
     */
    public function removeMember($slug, $userId)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        // Check if user can manage members
        if (!$organization->canEdit(Auth::user())) {
            abort(403, 'You do not have permission to remove members.');
        }

        // Cannot remove the owner
        if ($organization->owner_id == $userId) {
            return back()->with('error', 'Cannot remove the owner from the organization.');
        }

        // Find the user
        $user = User::findOrFail($userId);

        // Remove the member
        $organization->removeMember($user);

        return back()->with('success', 'Member removed successfully!');
    }

    /**
     * Delete the organization
     */
    public function destroy($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        // Only the owner can delete the organization
        if (Auth::id() !== $organization->owner_id) {
            abort(403, 'Only the owner can delete this organization.');
        }

        // Delete logo if it exists (simple way)
        if ($organization->logo && file_exists(public_path(str_replace('/storage/', 'storage/', $organization->logo)))) {
            unlink(public_path(str_replace('/storage/', 'storage/', $organization->logo)));
        }

        $organization->delete();

        return redirect()->route('organizations.index')
                       ->with('success', 'Organization deleted successfully!');
    }
}