<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblioteka</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body { font-family: 'Instrument Sans', sans-serif; }
        </style>
    @endif
</head>
<body class="bg-black text-white font-sans min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-md border border-purple-500">
        <h1 class="text-2xl font-bold text-center mb-6 text-purple-400">Login to Biblioteka</h1>

        @if ($errors->any())
            <div class="bg-red-900 border border-red-500 text-red-200 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="mt-1 block w-full rounded-md bg-white border-gray-300 text-black shadow-sm focus:border-purple-500 focus:ring-purple-500 px-3 py-2">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                <input type="password" id="password" name="password" required
                       class="mt-1 block w-full rounded-md bg-white border-gray-300 text-black shadow-sm focus:border-purple-500 focus:ring-purple-500 px-3 py-2">
            </div>

            <button type="submit" class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 font-medium">
                Login
            </button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-400">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300">Register here</a>
            </p>
        </div>
    </div>
</body>
</html>
