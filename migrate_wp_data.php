<?php
// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

try {
    $admin = User::where('role', 'admin')->first();
    if (!$admin) {
        $admin = User::first();
    }
    $authorId = $admin ? $admin->id : 1;

    echo "=== Migrating WordPress Posts ===\n";
    
    // Check if temp/imported tables exist
    $wpPostsTableExists = DB::select("SHOW TABLES LIKE 'wp_posts'");
    if (empty($wpPostsTableExists)) {
        echo "Error: wp_posts table not found. Make sure the SQL dump was imported.\n";
        exit(1);
    }

    // Get all publish posts from wp_posts
    $wpPosts = DB::select("SELECT * FROM wp_posts WHERE post_type = 'post' AND post_status = 'publish'");
    echo "Found " . count($wpPosts) . " published posts in wp_posts.\n";

    foreach ($wpPosts as $wpPost) {
        echo "Processing: {$wpPost->post_title} (ID: {$wpPost->ID})\n";

        // Check if slug or title already exists in posts table
        $existing = Post::where('title', $wpPost->post_title)
            ->orWhere('slug', $wpPost->post_name)
            ->first();

        if ($existing) {
            echo "-> Already exists in posts table (ID: {$existing->id}). Skipping.\n";
            continue;
        }

        // Try to find featured image
        $thumbnailPath = null;
        $thumbnailMeta = DB::selectOne("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_thumbnail_id'", [$wpPost->ID]);
        if ($thumbnailMeta) {
            $thumbnailId = $thumbnailMeta->meta_value;
            // Get the attachment post
            $attachment = DB::selectOne("SELECT guid FROM wp_posts WHERE ID = ? AND post_type = 'attachment'", [$thumbnailId]);
            if ($attachment) {
                // Get attached file meta which is the relative path in uploads
                $attachedFileMeta = DB::selectOne("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_wp_attached_file'", [$thumbnailId]);
                if ($attachedFileMeta) {
                    $thumbnailPath = 'uploads/' . $attachedFileMeta->meta_value;
                } else {
                    // Fallback to guid
                    $thumbnailPath = $attachment->guid;
                }
            }
        }

        // Clean up content: strip WordPress block comments like <!-- wp:paragraph -->
        $content = preg_replace('/<!--\s*\/?wp:.*?\s*-->/s', '', $wpPost->post_content);

        // Generate unique slug
        $slug = $wpPost->post_name ?: Str::slug($wpPost->post_title);
        $baseSlug = $slug;
        $count = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        // Create the post
        $post = Post::create([
            'title'            => $wpPost->post_title,
            'slug'             => $slug,
            'content'          => $content,
            'status'           => 'published',
            'thumbnail'        => $thumbnailPath,
            'author_id'        => $authorId,
            'published_at'     => $wpPost->post_date,
            'meta_title'       => mb_substr($wpPost->post_title, 0, 58),
            'meta_description' => mb_substr(strip_tags($content), 0, 155),
        ]);

        echo "-> Successfully migrated as Laravel Post ID: {$post->id}\n";
    }

    echo "=== Migration Complete ===\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
