<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Announcement;
use Illuminate\Support\Facades\Storage;

class SchemaMarkupService
{
    /**
     * Schema EducationalOrganization for profile pages.
     */
    public function educationalOrganization(array $settings): ?array
    {
        if (empty($settings['school_name']) || empty($settings['school_address'])) {
            return null;
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type'    => 'EducationalOrganization',
            'name'     => $settings['school_name'],
            'address'  => $settings['school_address'],
        ];

        if (!empty($settings['school_phone'])) {
            $schema['telephone'] = $settings['school_phone'];
        }
        if (!empty($settings['school_email'])) {
            $schema['email'] = $settings['school_email'];
        }

        return $schema;
    }

    /**
     * Schema NewsArticle for post detail page.
     */
    public function newsArticle(Post $post): ?array
    {
        if (!$post->title || !$post->published_at || !$post->author) {
            return null;
        }

        $schema = [
            '@context'         => 'https://schema.org',
            '@type'            => 'NewsArticle',
            'headline'         => $post->title,
            'datePublished'    => $post->published_at->toIso8601String(),
            'dateModified'     => $post->updated_at->toIso8601String(),
            'author'           => [
                '@type' => 'Person',
                'name'  => $post->author->name,
            ],
        ];

        if ($post->thumbnail) {
            $schema['image'] = url(Storage::url($post->thumbnail));
        }

        return $schema;
    }

    /**
     * Schema Article for announcements.
     */
    public function announcement(Announcement $ann): ?array
    {
        if (!$ann->title || !$ann->published_at) {
            return null;
        }

        return [
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => $ann->title,
            'description'   => substr(strip_tags($ann->content ?? ''), 0, 160),
            'datePublished' => $ann->published_at->toIso8601String(),
        ];
    }

    /**
     * Schema LocalBusiness.
     */
    public function localBusiness(array $settings): ?array
    {
        $required = ['school_name', 'school_address', 'school_phone', 'school_geo_lat', 'school_geo_lng'];
        foreach ($required as $key) {
            if (empty($settings[$key])) {
                return null;
            }
        }

        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'LocalBusiness',
            'name'        => $settings['school_name'],
            'address'     => $settings['school_address'],
            'telephone'   => $settings['school_phone'],
            'geo'         => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $settings['school_geo_lat'],
                'longitude' => $settings['school_geo_lng'],
            ],
        ];
    }

    /**
     * Schema Event for PPDB page.
     */
    public function ppdbEvent(array $settings): ?array
    {
        if (empty($settings['ppdb_start_date']) || empty($settings['ppdb_end_date'])) {
            return null;
        }

        return [
            '@context'  => 'https://schema.org',
            '@type'     => 'Event',
            'name'      => 'Penerimaan Peserta Didik Baru (PPDB) ' . ($settings['school_name'] ?? ''),
            'startDate' => $settings['ppdb_start_date'],
            'endDate'   => $settings['ppdb_end_date'],
            'organizer' => [
                '@type' => 'Organization',
                'name'  => $settings['school_name'] ?? '',
            ],
        ];
    }

    /**
     * Schema FAQPage.
     */
    public function faqPage(array $faqs): ?array
    {
        if (empty($faqs)) {
            return null;
        }

        $entities = array_map(fn($faq) => [
            '@type'          => 'Question',
            'name'           => $faq['question'] ?? $faq['name'] ?? '',
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer'] ?? ''],
        ], $faqs);

        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $entities,
        ];
    }
}
