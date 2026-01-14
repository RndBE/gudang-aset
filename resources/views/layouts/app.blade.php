<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gudang Aset</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<style>
    .btn-active {
        color: white;
        background-color: #C58D2A
    }

    .btn-outline-active {
        color: #C58D2A;
        border: 1.5px solid #C58D2A;

    }
</style>
@php
    $user = auth()->user();
@endphp

<body class="min-h-screen bg-white font-sans">
    <div class="p-4 h-20 bg-white shadow-[0_1px_5px_2px_rgba(0,0,0,0.25)] sticky top-0 z-50">
        <div class="font-semibold">Gudang Aset</div>
        <div class="text-xs text-gray-500 mt-1">
            {{ $user?->nama_lengkap ?? '-' }} · {{ $user?->username ?? '-' }}
        </div>
    </div>

    <div class="flex min-h-screen items-stretch">
        <aside class="sticky bg-white top-20 self-start min-h-screen shadow-xl z-10">
            @include('layouts._sidebar')
        </aside>

        <main class="flex-1 min-min-h-screen">
            <div class="p-6 bg-white min-min-h-screen">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
