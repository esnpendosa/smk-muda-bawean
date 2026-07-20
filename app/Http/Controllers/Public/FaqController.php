<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Services\CacheService;
use App\Services\SchemaMarkupService;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    protected CacheService $cacheService;
    protected SchemaMarkupService $schemaService;

    public function __construct(CacheService $cacheService, SchemaMarkupService $schemaService)
    {
        $this->cacheService = $cacheService;
        $this->schemaService = $schemaService;
    }

    /**
     * Display a listing of FAQs.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $faqs = Faq::active()
                ->where(function ($q) use ($search) {
                    $q->where('question', 'like', '%' . $search . '%')
                      ->orWhere('answer', 'like', '%' . $search . '%');
                })
                ->ordered()
                ->get();
        } else {
            $faqs = $this->cacheService->remember('faqs_list', 3600, function () {
                return Faq::active()->ordered()->get();
            });
        }

        $schema = $this->schemaService->faqPage($faqs->toArray());

        $seo = [
            'title' => 'Pertanyaan Umum (FAQ)',
            'description' => 'Jawaban atas berbagai pertanyaan yang sering diajukan mengenai SMK Muda Bawean.'
        ];

        return view('public.faq.index', compact('faqs', 'schema', 'seo'));
    }
}
