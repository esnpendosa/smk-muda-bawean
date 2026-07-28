@php
    $meta = seo_meta($seo ?? null);

    // Pastikan og:image selalu URL absolut dengan domain yang benar
    // Penting untuk WhatsApp/Facebook link preview
    if (!empty($meta['og_image'])) {
        $ogImage = $meta['og_image'];
        // Jika masih relative path, jadikan absolut
        if (!str_starts_with($ogImage, 'http')) {
            $ogImage = rtrim(config('app.url'), '/') . '/' . ltrim($ogImage, '/');
        }
        // Fix double slash dari APP_URL yang punya trailing slash
        $ogImage = preg_replace('#(https?://[^/]+)//+#', '$1/', $ogImage);
        // Ganti localhost dengan domain asli (untuk kasus APP_URL salah konfigurasi)
        foreach (['http://localhost', 'http://127.0.0.1', 'https://localhost', 'http://smkmudabawean.test'] as $local) {
            if (str_starts_with($ogImage, $local)) {
                $ogImage = 'https://smkmudabawean.sch.id/' . ltrim(substr($ogImage, strlen($local)), '/');
                break;
            }
        }
        $meta['og_image'] = $ogImage;
    }
@endphp
<title>{{ $meta['title'] }}</title>
<meta name="description" content="{{ $meta['description'] }}">
<link rel="canonical" href="{{ $meta['canonical'] }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ isset($seo['og_type']) ? $seo['og_type'] : 'website' }}">
<meta property="og:url" content="{{ $meta['og_url'] }}">
<meta property="og:title" content="{{ $meta['og_title'] }}">
<meta property="og:description" content="{{ $meta['og_description'] }}">
<meta property="og:site_name" content="SMK Muda Bawean">
@if(!empty($meta['og_image']))
<meta property="og:image" content="{{ $meta['og_image'] }}">
<meta property="og:image:secure_url" content="{{ str_replace('http://', 'https://', $meta['og_image']) }}">
<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $meta['og_title'] }}">
@endif

<!-- Twitter -->
<meta name="twitter:card" content="{{ !empty($meta['og_image']) ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:url" content="{{ $meta['og_url'] }}">
<meta name="twitter:title" content="{{ $meta['og_title'] }}">
<meta name="twitter:description" content="{{ $meta['og_description'] }}">
@if(!empty($meta['og_image']))
<meta name="twitter:image" content="{{ $meta['og_image'] }}">
@endif
