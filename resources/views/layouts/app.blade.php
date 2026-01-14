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
</style>

<body class="min-h-screen bg-gray-100 font-sans">
    <div class="p-4 h-20 bg-white shadow-[0_1px_5px_2px_rgba(0,0,0,0.25)] sticky top-0 z-50">
        <div class="font-semibold">Gudang Aset</div>
        <div class="text-xs text-gray-500 mt-1">
            {{ $user?->nama_lengkap ?? '-' }} · {{ $user?->username ?? '-' }}
        </div>
    </div>
    <div class="flex ">
        @include('layouts._sidebar')
        <main class="flex-1">
            <div class="p-6 bg-white h-full">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
