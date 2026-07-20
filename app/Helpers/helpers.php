<?php

if (!function_exists('seo_meta')) {
    /**
     * Merge and return SEO metadata.
     */
    function seo_meta(?array $seo = null): array
    {
        $schoolName = \App\Models\Setting::get('school_name', 'SMK Muda Bawean');
        $defaultTitle = $schoolName;
        $defaultDescription = 'Website Resmi ' . $schoolName;

        $inputTitle = $seo['title'] ?? null;
        if ($inputTitle) {
            $title = $inputTitle . ' | ' . $defaultTitle;
        } else {
            $title = $defaultTitle;
        }

        return [
            'title' => $title,
            'description' => $seo['description'] ?? $defaultDescription,
            'canonical' => $seo['canonical'] ?? request()->url(),
            'og_title' => $seo['og_title'] ?? $inputTitle ?? $defaultTitle,
            'og_description' => $seo['og_description'] ?? $seo['description'] ?? $defaultDescription,
            'og_image' => $seo['og_image'] ?? null,
            'og_url' => $seo['og_url'] ?? request()->url(),
        ];
    }
}

if (!function_exists('schema_json_ld')) {
    /**
     * Render schema markup script.
     */
    function schema_json_ld($schema): string
    {
        if (empty($schema)) {
            return '';
        }

        return '<script type="application/ld+json">' . 
            json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . 
            '</script>';
    }
}

if (!function_exists('theme_colors')) {
    /**
     * Retrieve current theme colors.
     */
    function theme_colors(): array
    {
        return app(\App\Services\ThemeService::class)->getColors();
    }
}

if (!function_exists('clean')) {
    /**
     * Clean HTML content using HtmlSanitizerService.
     */
    function clean(string $html): string
    {
        return app(\App\Services\HtmlSanitizerService::class)->clean($html);
    }
}
