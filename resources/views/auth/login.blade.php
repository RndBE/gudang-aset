<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-6 rounded border">
        <div class="text-xl font-semibold mb-4">Login</div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('login.post') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm mb-1">Username</label>
                <input name="username" value="{{ old('username') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <button class="w-full bg-black text-white rounded px-3 py-2">Masuk</button>
        </form>
    </div>
</body>

</html>
