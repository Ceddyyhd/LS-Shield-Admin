<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suggestion extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'area',
        'status',
        'notes',
        'is_anonymous'
    ];

    protected $appends = [
        'status_color',
        'upvotes_count',
        'downvotes_count',
        'user_voted',
        'user_vote'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(SuggestionVote::class);
    }

    public function upvotes(): HasMany
    {
        return $this->votes()->where('is_upvote', true);
    }

    public function downvotes(): HasMany
    {
        return $this->votes()->where('is_upvote', false);
    }

    public function getUserVotedAttribute(): bool
    {
        return $this->votes()->where('user_id', auth()->id())->exists();
    }

    public function getUserVoteAttribute()
    {
        return $this->votes()->where('user_id', auth()->id())->first();
    }

    public function getUpvotesCountAttribute()
    {
        return $this->upvotes()->count();
    }

    public function getDownvotesCountAttribute()
    {
        return $this->downvotes()->count();
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'eingegangen' => 'warning',
            'in_bearbeitung' => 'info',
            'rueckfragen' => 'primary',
            'abgeschlossen' => 'success',
            default => 'secondary'
        };
    }
}