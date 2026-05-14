<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Faq;
use App\Models\Gallery;

class SchemaOrgService
{
    /**
     * Verilen tip için schema.org JSON-LD listesini üretir.
     * type ∈ Hotel | LocalBusiness | FAQPage | WebPage
     */
    public function build(string $type, array $data = []): array
    {
        $schemas = [];

        switch ($type) {
            case 'Hotel':
                $schemas[] = $this->hotel($data);
                break;
            case 'LocalBusiness':
                $schemas[] = $this->localBusiness();
                break;
            case 'FAQPage':
                if (! empty($data['faqs'])) {
                    $schemas[] = $this->faqPage($data['faqs']);
                }
                break;
        }

        // Breadcrumb (varsa)
        if (! empty($data['breadcrumbs'])) {
            $schemas[] = $this->breadcrumb($data['breadcrumbs']);
        }

        return $schemas;
    }

    private function hotel(array $data = []): array
    {
        $images = array_map(
            fn ($g) => asset('storage/uploads/gallery/'.$g->image_path),
            Gallery::active()->limit(5)->get()->all()
        );

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Hotel',
            'name' => setting('site_name', 'Arsi Blue Beach Hotel'),
            'alternateName' => ['Arsi Blue Beach', 'Arsi Otel Alanya', 'Arsi Hotel'],
            'url' => url('/'),
            'description' => setting('site_tagline'),
            'starRating' => ['@type' => 'Rating', 'ratingValue' => '4'],
            'priceRange' => '₺₺',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => setting('address'),
                'addressLocality' => 'Alanya',
                'addressRegion' => 'Antalya',
                'addressCountry' => 'TR',
            ],
            'telephone' => setting('phone_landline'),
            'image' => $images,
        ];

        if (setting('site_logo')) {
            $schema['logo'] = asset('storage/uploads/'.setting('site_logo'));
        }

        $sameAs = array_filter([
            setting('instagram_url'),
            setting('facebook_url'),
            setting('tripadvisor_url'),
            setting('google_business_url'),
        ]);
        if (! empty($sameAs)) {
            $schema['sameAs'] = array_values($sameAs);
        }

        // Amenities (sabit liste — spec § 9)
        $schema['amenityFeature'] = array_map(
            fn ($name) => ['@type' => 'LocationFeatureSpecification', 'name' => $name, 'value' => true],
            ['Açık Havuz', 'Aqua Park', 'Çocuk Havuzu', 'Kapalı Havuz', 'Wi-Fi', 'Klima', 'Plaj', 'Açık Büfe', 'Animasyon']
        );

        // Aktif kampanya → makesOffer
        $campaign = Campaign::active()->orderBy('sort_order')->first();
        if ($campaign && $campaign->new_price) {
            $schema['makesOffer'] = [
                '@type' => 'Offer',
                'name' => $campaign->title,
                'description' => $campaign->subtitle ?: $campaign->description,
                'price' => (string) $campaign->new_price,
                'priceCurrency' => 'TRY',
                'availability' => 'https://schema.org/InStock',
                'validThrough' => $campaign->valid_until?->toIso8601String(),
            ];
        }

        return $schema;
    }

    private function localBusiness(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => setting('site_name'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => setting('address'),
                'addressLocality' => 'Alanya',
                'addressRegion' => 'Antalya',
                'addressCountry' => 'TR',
            ],
            'telephone' => setting('phone_landline'),
            'url' => url('/'),
            'openingHours' => 'Mo-Su 00:00-23:59',
        ];
    }

    private function faqPage(iterable $faqs): array
    {
        $main = [];
        foreach ($faqs as $faq) {
            $main[] = [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq->answer,
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $main,
        ];
    }

    /**
     * @param array<int, array{name:string,url:string}> $items
     */
    private function breadcrumb(array $items): array
    {
        $list = [];
        foreach ($items as $i => $item) {
            $list[] = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $list,
        ];
    }
}
