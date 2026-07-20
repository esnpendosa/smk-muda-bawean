<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'parent_id',
        'user_id',
        'name',
        'email',
        'content',
        'status',
        'upvotes',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the post that this comment belongs to.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that wrote the comment (if authenticated).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment of this comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get all replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->where('status', 'approved')->orderBy('created_at', 'asc');
    }

    /**
     * Get the commenter name (registered user's name or guest name).
     */
    public function getCommenterNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $this->name ?: 'Guest';
    }

    /**
     * Get the commenter avatar URL (Gravatar based on email).
     */
    public function getAvatarUrlAttribute(): string
    {
        $email = $this->user ? $this->user->email : $this->email;
        $hash = md5(strtolower(trim($email ?? '')));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=100";
    }
}
