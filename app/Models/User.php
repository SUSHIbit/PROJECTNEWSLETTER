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
        ];
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
     * Update last active timestamp
     */
    public function updateLastActive()
    {
        $this->update(['last_active_at' => now()]);
    }
}