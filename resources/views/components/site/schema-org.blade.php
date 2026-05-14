@props(['type' => 'WebPage', 'data' => []])

@php
    // SchemaOrgService Adım 13'te eklendi — direkt çağırıyoruz
    $service = app(\App\Services\SchemaOrgService::class);
    $schemas = $service->build($type, $data);
@endphp

@foreach ($schemas as $schema)
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endforeach

{{-- Page-specific custom schema (seo_metas.schema_json) --}}
@if (! empty($seo?->schema_json))
    <script type="application/ld+json">{!! $seo->schema_json !!}</script>
@endif
