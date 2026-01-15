<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
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

<body class="min-h-screen bg-white overflow-hidden">
    <div class="min-h-screen flex items-center">
        <div class="w-full max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-12">

            {{-- LEFT --}}
            <div class="md:col-span-6 hidden md:block ">
                <div class="h-full min-h-[95vh] bg-center bg-no-repeat bg-cover rounded-2xl flex items-end"
                    style="background-image: url('{{ Vite::asset('resources/icon/hero_login.png') }}');">
                    <div class="px-8 mb-8">
                        <div class="text-3xl font-bold text-white">Advance Warehouse Smart System</div>
                        <div class="text-md font-bold text-white">Kontrol Stok Lebih Rapi, Operasional Lebih Pasti</div>
                        <div class="text-md text-white mt-4">Sistem gudang yang membantu menjaga ketersediaan barang.
                            Minim kesalahan, maksimal efisiensi, setiap hari.</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="md:col-span-6 flex items-center justify-center p-6">
                <div class="w-full max-w-md bg-white p-6 ">
                    <div class="text-2xl font-bold mb-2 text-center">Masuk ke Akun </div>
                    <div class="text-sm text-center mb-6">Masukkan detail akun Anda untuk melanjutkan</div>
                    @if ($errors->any())
                        <div class="mb-4 text-sm text-red-600">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('login.post') }}" class="space-y-4">
                        @csrf

                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.682 18.682 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>

                            <input name="username" value="{{ old('username') }}" placeholder="Username"
                                class="w-full text-sm  bg-[#f6f6f6] rounded-lg pl-10 pr-3 py-3 focus:outline-none focus:ring-1 focus:ring-gray-200 focus:shadow-md"
                                required>
                        </div>


                        <div class="relative ">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25V10.5H6A2.25 2.25 0 0 0 3.75 12.75v6A2.25 2.25 0 0 0 6 21h12a2.25 2.25 0 0 0 2.25-2.25v-6A2.25 2.25 0 0 0 18 10.5h-.75V6.75A5.25 5.25 0 0 0 12 1.5Zm3.75 9V6.75a3.75 3.75 0 1 0-7.5 0v3.75h7.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>

                            <input id="password" type="password" name="password" placeholder="Password"
                                class="w-full text-sm bg-[#f6f6f6] rounded-lg pl-10 pr-11 py-3     focus:outline-none focus:ring-1 focus:ring-gray-200 focus:shadow-md"
                                required>

                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-700">

                                <svg id="iconEye" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                    viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 5.25c-4.478 0-8.268 2.943-9.543 7.003a.75.75 0 0 0 0 .494C3.732 16.807 7.522 19.75 12 19.75c4.478 0 8.268-2.943 9.543-7.003a.75.75 0 0 0 0-.494C20.268 8.193 16.478 5.25 12 5.25Zm0 11a3.25 3.25 0 1 1 0-6.5 3.25 3.25 0 0 1 0 6.5Z" />
                                </svg>

                                <svg id="iconEyeSlash" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden"
                                    viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l2.05 2.05A10.69 10.69 0 0 0 2.457 11.75a.75.75 0 0 0 0 .494C3.732 16.807 7.522 19.75 12 19.75c2.032 0 3.93-.51 5.57-1.4l2.9 2.9a.75.75 0 1 0 1.06-1.06L3.53 2.47ZM12 18.25c-3.81 0-7.047-2.45-8.06-5.75a9.2 9.2 0 0 1 1.67-3.1l2.2 2.2a3.25 3.25 0 0 0 4.59 4.59l1.93 1.93c-.74.2-1.52.31-2.33.31Zm3.4-3.53-2.11-2.11a3.25 3.25 0 0 0-4.4-4.4L6.7 6.02A9.18 9.18 0 0 1 12 5.25c3.81 0 7.047 2.45 8.06 5.75a9.21 9.21 0 0 1-2.21 3.64l-2.45-2.45Z" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-10">
                            <div class="text-sm fw-semibold text-end mb-2 text-[#C58D2A]">Lupa Kata Sandi ?</div>
                            <button class="w-full btn-active rounded-lg px-3 py-2 hover:opacity-90">
                                Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>


{{-- Toggle password --}}
<script>
    const password = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const iconEye = document.getElementById('iconEye');
    const iconEyeSlash = document.getElementById('iconEyeSlash');

    togglePassword.addEventListener('click', () => {
        const isHidden = password.type === 'password';
        password.type = isHidden ? 'text' : 'password';

        iconEye.classList.toggle('hidden', isHidden);
        iconEyeSlash.classList.toggle('hidden', !isHidden);
    });
</script>

</html>
