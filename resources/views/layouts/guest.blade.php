<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Masuk' }} — Padel YUK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-950 text-white flex flex-col items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <a href="{{ route('home') }}" class="flex items-center justify-center mb-8 group">
            <div class="w-32 h-32 overflow-hidden group-hover:scale-110 transition-transform">
                <img src="{{ asset('images/Yuk-P.png') }}" alt="Padel YUK" class="w-full h-full object-contain drop-shadow-xl">
            </div>
        </a>
        {{ $slot }}
    </div>
</body>
</html>
