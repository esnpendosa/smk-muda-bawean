<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\SchemaMarkupService;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    protected SchemaMarkupService $schemaService;

    public function __construct(SchemaMarkupService $schemaService)
    {
        $this->schemaService = $schemaService;
    }

    /**
     * Display a listing of the news.
     */
    public function index()
    {
        $posts = Post::published()->with('author')->orderBy('published_at', 'desc')->paginate(9);
        
        $seo = [
            'title' => 'Kabar & Berita',
            'description' => 'Ikuti perkembangan terbaru dan kegiatan akademik SMK Muda Bawean.'
        ];

        return view('public.berita.index', compact('posts', 'seo'));
    }

    /**
     * Display the specified news.
     */
    public function show(string $slug)
    {
        if (Post::onlyTrashed()->where('slug', $slug)->exists()) {
            abort(410);
        }

        $post = Post::published()->with('author')->where('slug', $slug)->firstOrFail();
        
        $schema = $this->schemaService->newsArticle($post);

        $seo = [
            'title'       => $post->meta_title ?: $post->title,
            'description' => $post->meta_description ?: Str::limit(strip_tags($post->content), 155),
            'og_image'    => $post->thumbnail_url,
            'og_type'     => 'article',
            'og_url'      => url()->current(),
            'canonical'   => url()->current(),
        ];

        return view('public.berita.show', compact('post', 'schema', 'seo'));
    }
}
