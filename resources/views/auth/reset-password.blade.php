<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AWASS - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" type="image/svg+xml" href="{{ Vite::asset('resources/icon/head-icon.png') }}">
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
            <div class="text-lg font-semibold text-gray-900">Reset Kata Sandi</div>
            <div class="text-sm text-gray-500 mt-1">Buat password baru untuk akun kamu.</div>

            <form method="POST" action="{{ route('password.update') }}" class="mt-5 space-y-4">
                @csrf

                {{-- <input type="hidden" name="token" value="{{ $token }}"> --}}
                <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">
                {{-- <input type="hidden" name="email" value="{{ $email }}"> --}}
                <input type="hidden" name="email" value="{{ $email ?? request()->query('email') }}">

                <div>
                    <label class="text-sm font-medium text-gray-800">Password Baru</label>
                    <input type="password" name="password"
                        class="mt-1 w-full border rounded-xl text-sm border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500"
                        required>
                    @error('password')
                        <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-800">Ulangi Password Baru</label>
                    <input type="password" name="password_confirmation"
                        class="mt-1 w-full border rounded-xl text-sm border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500"
                        required>
                </div>

                @error('email')
                    <div class="text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                        {{ $message }}</div>
                @enderror

                <button type="submit"
                    class="w-full rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold py-2.5">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>

</body>
