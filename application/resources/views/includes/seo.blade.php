@if ($seo)
    @php
        $metaTitle = $general->siteName(__($pageTitle));
        $metaDescription = $seo->description;
        $metaImage = getImage(getFilePath('seo') . '/' . $seo->image);
        if (isset($product) && is_object($product)) {
            $metaTitle = $product->meta_title ?? '';
            $metaDescription = $product->meta_description ?? '';
            $metaImage =  getImage(getFilePath('product') . '/' . $product?->firstImage?->image ?? $product['image']);
        }
    @endphp
    <meta name="title" Content="{{ $metaTitle }}">
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ implode(',', $seo->keywords) }}">
    <link rel="shortcut icon" href="{{ getImage(getFilePath('logoIcon') . '/favicon.png') }}" type="image/x-icon">

    {{-- <!-- Apple Stuff --> --}}
    <link rel="apple-touch-icon" href="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ $general->siteName($pageTitle) }}">
    {{-- <!-- Google / Search Engine Tags --> --}}
    <meta itemprop="name" content="{{ $general->siteName($pageTitle) }}">
    <meta itemprop="description" content="{{ $metaDescription }}">
    <meta itemprop="image" content="{{ $metaImage }}">
    {{-- <!-- Facebook Meta Tags --> --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ isset($product) && is_object($product) ? $product->meta_title: $seo->social_title }}">
    <meta property="og:description" content="{{ isset($product) && is_object($product) ? $product->meta_description: $seo->social_description }}">
    <meta property="og:image" content="{{ $metaImage }}" />
    <meta property="og:image:type"
        content="image/{{ pathinfo(getImage(getFilePath('seo')) . '/' . $seo->image)['extension'] }}" />
    @php $socialImageSize = explode('x', getFileSize('seo')) @endphp
    <meta property="og:image:width" content="{{ $socialImageSize[0] }}" />
    <meta property="og:image:height" content="{{ $socialImageSize[1] }}" />
    <meta property="og:url" content="{{ url()->current() }}">
    {{-- <!-- Twitter Meta Tags --> --}}
    <meta name="twitter:card" content="summary_large_image">
@endif
