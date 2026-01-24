<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AWASS - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    {{-- <link rel="icon" type="image/svg+xml" href="{{ Vite::asset('resources/icon/head-icon.png') }}"> --}}
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sonsie+One&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<style>
    .font-sonsie {
        font-family: "Sonsie One", serif;
    }

    .btn-active {
        color: white;
        background-color: #C58D2A
    }

    .btn-outline-active {
        color: #C58D2A;
        border: 1.5px solid #C58D2A;

    }
</style>

<body class="min-h-screen bg-[#F6F6F6] overflow-hidden">
    <div class="min-h-screen flex items-center">
        <div class="max-w-md w-full mx-auto bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <div class="text-lg font-semibold text-gray-900">Lupa Kata Sandi</div>
            <div class="text-sm text-gray-500 mt-1">Masukkan email yang terdaftar untuk menerima link reset password.
            </div>

            @if (session('success'))
                <div class="mt-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-5 space-y-4">
                @csrf

                <div>
                    <label class="text-sm font-medium text-gray-800">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="mt-1 w-full border rounded-xl text-sm border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500"
                        required>
                    @error('email')
                        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold py-2.5 cursor-pointer">
                    Kirim Link Reset
                </button>

                <a href="{{ route('login') }}" class="block text-center text-sm text-gray-600 hover:underline">
                    Kembali ke Login
                </a>
            </form>
        </div>
    </div>

</body>
