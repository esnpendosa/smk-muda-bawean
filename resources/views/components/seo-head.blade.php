@php
    $meta = seo_meta($seo ?? null);
@endphp
<title>{{ $meta['title'] }}</title>
<meta name="description" content="{{ $meta['description'] }}">
<link rel="canonical" href="{{ $meta['canonical'] }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $meta['og_url'] }}">
<meta property="og:title" content="{{ $meta['og_title'] }}">
<meta property="og:description" content="{{ $meta['og_description'] }}">
@if(!empty($meta['og_image']))
<meta property="og:image" content="{{ $meta['og_image'] }}">
@endif

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $meta['og_url'] }}">
<meta name="twitter:title" content="{{ $meta['og_title'] }}">
<meta name="twitter:description" content="{{ $meta['og_description'] }}">
@if(!empty($meta['og_image']))
<meta name="twitter:image" content="{{ $meta['og_image'] }}">
@endif
