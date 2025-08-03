<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'featured_image',
        'category',
        'status',
        'views',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'views' => 'integer',
        ];
    }

    /**
     * Get the user who created this post
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all comments for this post
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get only top-level comments (no parent)
     */
    public function topLevelComments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    /**
     * Get all likes for this post
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Check if a user has liked this post
     */
    public function isLikedBy($user)
    {
        if (!$user) {
            return false;
        }
        
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the like count for this post
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * Get the comments count for this post
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    /**
     * Scope to only get published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to order by latest first
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the excerpt of the post content
     */
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    /**
     * Get the excerpt with custom length
     */
    public function excerpt($length = 150)
    {
        return Str::limit(strip_tags($this->content), $length);
    }

    /**
     * Get the reading time estimate
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // Average reading speed
        return $minutes . ' min read';
    }

    /**
     * Increment the view count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}