<?php

namespace Tests\Feature\Property;

use App\Services\HtmlSanitizerService;
use Tests\TestCase;

class HtmlSanitizerPropertyTest extends TestCase
{
    /**
     * P3: input dengan tag berbahaya → output tidak mengandung tag tersebut
     */
    public function test_sanitizer_safety_invariant(): void
    {
        $sanitizer = new HtmlSanitizerService();
        $dangerous = ['<script>', '</script>', '<iframe', '<object', '<embed', 'onclick=', 'onerror=', 'onload='];

        $maliciousInputs = [
            '<script>alert("xss")</script><p>text</p>',
            '<iframe src="evil.com"></iframe>',
            '<p onclick="evil()">text</p>',
            '<img src="x" onerror="alert(1)">',
            '<object data="evil"></object>',
        ];

        foreach ($maliciousInputs as $input) {
            $output = $sanitizer->clean($input);
            foreach ($dangerous as $tag) {
                $this->assertStringNotContainsStringIgnoringCase($tag, $output,
                    "Output mengandung string berbahaya '{$tag}' dari input: {$input}");
            }
        }
    }

    /**
     * P4: input dengan hanya tag diizinkan → output mempertahankan konten
     */
    public function test_sanitizer_preserves_allowed_content(): void
    {
        $sanitizer = new HtmlSanitizerService();
        $allowedInputs = [
            '<p>Paragraf biasa</p>',
            '<p><strong>Bold</strong> dan <em>italic</em></p>',
            '<ul><li>Item 1</li><li>Item 2</li></ul>',
            '<h2>Heading dua</h2>',
        ];

        foreach ($allowedInputs as $input) {
            $output = $sanitizer->clean($input);
            $textContent = strip_tags($input);
            $this->assertStringContainsString($textContent, strip_tags($output));
        }
    }
}
