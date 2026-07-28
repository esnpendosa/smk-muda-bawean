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
     * File uploads disimpan langsung di public/uploads/ oleh ImageProcessingService.
     * Selalu return URL absolut dengan domain production.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) return null;

        // Sudah full URL
        if (str_starts_with($this->thumbnail, 'http')) {
            return $this->ensureProductionUrl($this->thumbnail);
        }

        // Format upload baru: "uploads/uuid.jpg" → ada di public/uploads/
        if (str_starts_with($this->thumbnail, 'uploads/')) {
            return $this->ensureProductionUrl(asset($this->thumbnail));
        }

        // Gambar statis seeder: "images/xxx.png" → ada di public/images/
        if (str_starts_with($this->thumbnail, 'images/')) {
            return $this->ensureProductionUrl(asset($this->thumbnail));
        }

        // Legacy: path lain via storage symlink
        return $this->ensureProductionUrl(asset('storage/' . $this->thumbnail));
    }

    /**
     * Ganti domain localhost/lokal dengan domain production.
     * Dibutuhkan karena APP_URL di lokal adalah http://localhost.
     */
    private function ensureProductionUrl(string $url): string
    {
        $production = 'https://smkmudabawean.sch.id';

        // Fix trailing slash di APP_URL yang menyebabkan double slash
        // e.g. "https://smkmudabawean.sch.id//uploads/..." → benar
        $url = preg_replace('#(https?://[^/]+)//+#', '$1/', $url);

        foreach ([
            'http://localhost',
            'https://localhost',
            'http://127.0.0.1',
            'https://127.0.0.1',
            'http://smkmudabawean.test',
            'https://smkmudabawean.test',
        ] as $local) {
            if (str_starts_with($url, $local)) {
                return $production . '/' . ltrim(substr($url, strlen($local)), '/');
            }
        }

        return $url;
    }
}
