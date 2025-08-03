<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'user_id',
        'role',
        'joined_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }

    /**
     * Get the organization this membership belongs to
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user this membership belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the member is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the member is an editor
     */
    public function isEditor()
    {
        return $this->role === 'editor';
    }

    /**
     * Check if the member can edit content
     */
    public function canEditContent()
    {
        return in_array($this->role, ['admin', 'editor']);
    }
}