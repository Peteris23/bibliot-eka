<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us - Biblioteka</title>
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
<body class="bg-black text-white font-sans">
    <!-- Navigation -->
    <nav class="bg-black border-b border-purple-500">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-purple-400">Biblioteka</h1>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">Home</a>
                    <a href="/search" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">Search</a>
                    <a href="{{ url('/books/create') }}" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">Create Books</a>
                    <a href="/about" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">About Us</a>
                    <a href="{{ route('login') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Notification -->
    <div id="notification" class="fixed top-4 right-4 bg-green-600 dark:bg-green-600 text-white px-4 py-2 rounded shadow-lg hidden z-50">
        <p id="notificationMessage"></p>
    </div>

    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-purple-900 to-black py-12">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-3 text-white">About Biblioteka</h1>
            <p class="text-base md:text-lg text-gray-300">Your Digital Library Experience</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-8 bg-gray-900">
        <div class="max-w-2xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-2 text-center text-purple-400">Who We Are</h2>
            <p class="text-center text-gray-500 text-xs mb-6">Your premier digital library platform</p>
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-gray-800 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">📚 Vast Collection</h3>
                    <p class="text-gray-400 text-xs">Thousands of books across all genres and categories at your fingertips.</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">⚡ Fast Search</h3>
                    <p class="text-gray-400 text-xs">Find books instantly with our powerful search engine and filters.</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">✅ Easy Management</h3>
                    <p class="text-gray-400 text-xs">Borrow and track loans with our user-friendly interface.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-8 bg-black">
        <div class="max-w-2xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-2 text-center text-purple-400">Contact Us</h2>
            <p class="text-center text-gray-500 text-xs mb-6">Have questions? We'd love to hear from you</p>
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">📧 Email</h3>
                    <p class="text-gray-400 text-xs">support@biblioteka.com</p>
                </div>
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">📞 Phone</h3>
                    <p class="text-gray-400 text-xs">+1 (555) 123-4567</p>
                </div>
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">📍 Address</h3>
                    <p class="text-gray-400 text-xs">123 Library Street, Book City, BC 12345</p>
                </div>
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">🕐 Hours</h3>
                    <p class="text-gray-400 text-xs">Monday - Friday: 9:00 AM - 6:00 PM</p>
                </div>
            </div>
            <p class="text-xs text-gray-600 mt-6 text-center">We typically respond within 24 hours</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black border-t border-purple-500/30 py-4">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p class="text-gray-500 text-xs">&copy; 2024 Biblioteka. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>