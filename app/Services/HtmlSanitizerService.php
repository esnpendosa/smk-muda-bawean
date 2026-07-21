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
            'p,br,strong,em,b,i,u,ul,ol,li,a[href|title|target],h2,h3,h4,h5,h6,' .
            'blockquote,img[src|alt|width|height|style],table,thead,tbody,tr,th,td,div[style|class],span[style|class]'
        );

        // Force target="_blank" and noopener/noreferrer for external links
        $config->set('HTML.TargetBlank', true);
        $config->set('HTML.Nofollow', false);

        // Allow data: URI scheme for base64 images
        $config->set('URI.AllowedSchemes', [
            'http' => true,
            'https' => true,
            'data' => true
        ]);

        // Allow basic CSS properties for inline styling and alignment
        $config->set('CSS.AllowedProperties', [
            'text-align', 'font-weight', 'font-style', 'text-decoration',
            'color', 'background-color', 'width', 'height', 'margin', 'float'
        ]);

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
