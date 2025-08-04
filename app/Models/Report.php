<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reporter_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'status',
        'reviewed_by',
        'admin_notes',
        'reviewed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * Get the user who made the report
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Get the admin who reviewed the report
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the reportable model (post, comment, or user)
     */
    public function reportable()
    {
        return $this->morphTo();
    }

    /**
     * Scope to get pending reports
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get reviewed reports
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    /**
     * Mark report as reviewed
     */
    public function markAsReviewed($adminId, $notes = null)
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_by' => $adminId,
            'admin_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Mark report as resolved
     */
    public function markAsResolved($adminId, $notes = null)
    {
        $this->update([
            'status' => 'resolved',
            'reviewed_by' => $adminId,
            'admin_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Mark report as dismissed
     */
    public function markAsDismissed($adminId, $notes = null)
    {
        $this->update([
            'status' => 'dismissed',
            'reviewed_by' => $adminId,
            'admin_notes' => $notes,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Get formatted reason
     */
    public function getFormattedReasonAttribute()
    {
        return match($this->reason) {
            'spam' => 'Spam',
            'inappropriate' => 'Inappropriate Content',
            'harassment' => 'Harassment',
            'fake_news' => 'Fake News',
            'copyright' => 'Copyright Violation',
            'other' => 'Other',
            default => ucfirst($this->reason)
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'reviewed' => 'blue',
            'resolved' => 'green',
            'dismissed' => 'gray',
            default => 'gray'
        };
    }
}