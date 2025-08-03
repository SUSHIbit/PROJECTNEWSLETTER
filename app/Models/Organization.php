<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Organization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'email',
    ];

    /**
     * Get the owner of this organization
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all members of this organization
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'organization_members')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Get all organization memberships
     */
    public function memberships()
    {
        return $this->hasMany(OrganizationMember::class);
    }

    /**
     * Get admins of this organization
     */
    public function admins()
    {
        return $this->members()->wherePivot('role', 'admin');
    }

    /**
     * Get editors of this organization
     */
    public function editors()
    {
        return $this->members()->wherePivot('role', 'editor');
    }

    /**
     * Check if a user is a member of this organization
     */
    public function hasMember($user)
    {
        if (!$user) {
            return false;
        }
        
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if a user is an admin of this organization
     */
    public function isAdmin($user)
    {
        if (!$user) {
            return false;
        }
        
        return $this->members()
                    ->where('user_id', $user->id)
                    ->wherePivot('role', 'admin')
                    ->exists();
    }

    /**
     * Check if a user can edit this organization
     */
    public function canEdit($user)
    {
        if (!$user) {
            return false;
        }
        
        // Owner can always edit
        if ($this->owner_id === $user->id) {
            return true;
        }
        
        // Admins can edit
        return $this->isAdmin($user);
    }

    /**
     * Add a member to this organization
     */
    public function addMember($user, $role = 'member')
    {
        // Check if user is already a member
        if ($this->hasMember($user)) {
            return false;
        }
        
        return $this->members()->attach($user->id, [
            'role' => $role,
            'joined_at' => now(),
        ]);
    }

    /**
     * Remove a member from this organization
     */
    public function removeMember($user)
    {
        return $this->members()->detach($user->id);
    }

    /**
     * Get the logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return $this->logo;
        }
        
        // Return a default logo based on organization name
        $initials = strtoupper(substr($this->name, 0, 2));
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=1a365d&color=ffffff&size=200";
    }

    /**
     * Automatically generate slug from name
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($organization) {
            if (empty($organization->slug)) {
                $organization->slug = Str::slug($organization->name);
            }
        });
    }
}