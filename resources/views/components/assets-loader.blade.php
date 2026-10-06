@php
    $manifestPath = public_path('build/manifest.json');
    $manifest = [];

    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true) ?? [];
    }

    $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
    $jsFile  = $manifest['resources/js/app.js']['file'] ?? null;
@endphp

@if($cssFile)
    <link rel="stylesheet" href="/build/{{ $cssFile }}">
@endif

@if($jsFile)
    <script type="module" src="/build/{{ $jsFile }}"></script>
@endif