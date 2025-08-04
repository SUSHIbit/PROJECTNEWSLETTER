<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'account_type',
        'is_admin',
        'profile_picture',
        'bio',
        'location',
        'website',
        'last_active_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_active_at' => 'datetime',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get all posts created by this user
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all published posts by this user
     */
    public function publishedPosts()
    {
        return $this->hasMany(Post::class)->published();
    }

    /**
     * Get all comments made by this user
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all likes made by this user
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get all reports made by this user
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    /**
     * Get all reports reviewed by this user (if admin)
     */
    public function reviewedReports()
    {
        return $this->hasMany(Report::class, 'reviewed_by');
    }

    /**
     * Get users this user is following
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    /**
     * Get users following this user
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    /**
     * Get organizations owned by this user
     */
    public function ownedOrganizations()
    {
        return $this->hasMany(Organization::class, 'owner_id');
    }

    /**
     * Get organizations this user is a member of
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_members')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Get organization memberships
     */
    public function organizationMemberships()
    {
        return $this->hasMany(OrganizationMember::class);
    }

    /**
     * Check if this user is following another user
     */
    public function isFollowing($user)
    {
        if (!$user) {
            return false;
        }
        
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Follow another user
     */
    public function follow($user)
    {
        if (!$user || $user->id === $this->id) {
            return false; // Can't follow yourself
        }
        
        // Check if already following
        if ($this->isFollowing($user)) {
            return false; // Already following
        }
        
        return $this->following()->attach($user->id);
    }

    /**
     * Unfollow another user
     */
    public function unfollow($user)
    {
        if (!$user) {
            return false;
        }
        
        return $this->following()->detach($user->id);
    }

    /**
     * Like a post or comment
     */
    public function like($model)
    {
        return $this->likes()->create([
            'likeable_type' => get_class($model),
            'likeable_id' => $model->id,
        ]);
    }

    /**
     * Unlike a post or comment
     */
    public function unlike($model)
    {
        return $this->likes()
                    ->where('likeable_type', get_class($model))
                    ->where('likeable_id', $model->id)
                    ->delete();
    }

    /**
     * Check if user has liked a model
     */
    public function hasLiked($model)
    {
        return $this->likes()
                    ->where('likeable_type', get_class($model))
                    ->where('likeable_id', $model->id)
                    ->exists();
    }

    /**
     * Get the user's profile picture URL
     * If no profile picture, return a default placeholder
     */
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            // If using Cloudinary, return the Cloudinary URL
            return $this->profile_picture;
        }
        
        // Return a default avatar based on user's initials
        $initials = strtoupper(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=1a365d&color=ffffff&size=200";
    }

    /**
     * Get user's display name (username or name)
     */
    public function getDisplayNameAttribute()
    {
        return $this->username ?: $this->name;
    }

    /**
     * Get followers count
     */
    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }

    /**
     * Get following count
     */
    public function getFollowingCountAttribute()
    {
        return $this->following()->count();
    }

    /**
     * Get posts count
     */
    public function getPostsCountAttribute()
    {
        return $this->publishedPosts()->count();
    }

    /**
     * Check if user is an organization
     */
    public function isOrganization()
    {
        return $this->account_type === 'organization';
    }

    /**
     * Check if user is personal account
     */
    public function isPersonal()
    {
        return $this->account_type === 'personal';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->is_admin === true;
    }

    /**
     * Check if user can access admin panel
     */
    public function canAccessAdmin()
    {
        return $this->isAdmin();
    }

    /**
     * Update last active timestamp
     */
    public function updateLastActive()
    {
        $this->update(['last_active_at' => now()]);
    }

    /**
     * Simple check if user has liked a post or comment
     */
    public function hasLikedSimple($model)
    {
        $modelClass = get_class($model);
        
        return \App\Models\Like::where('user_id', $this->id)
            ->where('likeable_type', $modelClass)
            ->where('likeable_id', $model->id)
            ->exists();
    }

    /**
     * Scope to get only admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope to get non-admin users
     */
    public function scopeNonAdmins($query)
    {
        return $query->where('is_admin', false);
    }

    /**
     * Scope to get verified users
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope to get suspended users (using email_verified_at as suspension flag)
     */
    public function scopeSuspended($query)
    {
        return $query->whereNull('email_verified_at');
    }
}