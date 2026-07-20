<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizerService
{
    private HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();

        // Allowed HTML tags and attributes
        $config->set('HTML.Allowed',
            'p,br,strong,em,b,i,u,ul,ol,li,a[href|title|target],h2,h3,h4,' .
            'blockquote,img[src|alt|width|height],table,thead,tbody,tr,th,td'
        );

        // Force target="_blank" and noopener/noreferrer for external links
        $config->set('HTML.TargetBlank', true);
        $config->set('HTML.Nofollow', false);

        // Disallow all custom/inline styles to maintain theme consistency and prevent CSS injection
        $config->set('CSS.AllowedProperties', []);

        // Cache path for serializer
        $config->set('Cache.SerializerPath', storage_path('app/htmlpurifier'));

        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Clean the HTML input using the purifier.
     */
    public function clean(string $html): string
    {
        return $this->purifier->purify($html);
    }
}
