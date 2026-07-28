<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'status',
        'meta_title',
        'meta_description',
        'author_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the comments for the post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('status', 'approved')->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    /**
     * Scope to only include published posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    /**
     * Get the user that authored the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the publicly accessible thumbnail URL.
     * Handles both storage-based uploads and public/images/ assets.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) return null;
        // Already a full URL (e.g. http/https)
        if (str_starts_with($this->thumbnail, 'http')) return $this->thumbnail;
        // Public images from seeder (images/xxx.png) — served directly from public/
        if (str_starts_with($this->thumbnail, 'images/')) return asset($this->thumbnail);
        // New uploads saved directly to public/uploads/ (no symlink needed)
        if (str_starts_with($this->thumbnail, 'uploads/')) return asset($this->thumbnail);
        // Legacy: old uploads via Storage::disk('public') symlink
        return asset('storage/' . $this->thumbnail);
    }
}
