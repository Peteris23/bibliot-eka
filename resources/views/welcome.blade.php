@php
    $locale = session('locale', 'lv');
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $locale === 'lv' ? 'Biblioteka - Tava Digitālā Bibliotēka' : 'Library - Your Digital Library' }}</title>
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
                    <a href="/" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">{{ $locale === 'lv' ? 'Sākums' : 'Home' }}</a>
                    
                    @auth
                        {{-- Authenticated users (Admin or User) --}}
                        <a href="{{ route('library') }}" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">{{ $locale === 'lv' ? 'Bibliotēka' : 'Library' }}</a>
                        <a href="/search" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">{{ $locale === 'lv' ? 'Meklēt' : 'Search' }}</a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ url('/books/create') }}" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">{{ $locale === 'lv' ? 'Pievienot Grāmatas' : 'Add Books' }}</a>
                        @endif
                        <a href="/about" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">{{ $locale === 'lv' ? 'Par Mums' : 'About' }}</a>
                        
                        {{-- User info badge --}}
                        <span class="text-gray-400 text-xs px-2 py-1 bg-gray-800 rounded">
                            @if(auth()->user()->isAdmin())
                                🛡️ Admin
                            @else
                                👤 {{ $locale === 'lv' ? 'Lietotājs' : 'User' }}
                            @endif
                        </span>
                    @else
                        {{-- Guest users (not authenticated) --}}
                        <a href="/search" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">{{ $locale === 'lv' ? 'Meklēt' : 'Search' }}</a>
                        <a href="/about" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">{{ $locale === 'lv' ? 'Par Mums' : 'About' }}</a>
                        
                        {{-- Guest badge --}}
                        <span class="text-gray-500 text-xs px-2 py-1 bg-gray-800 rounded">
                            👁️ {{ $locale === 'lv' ? 'Viesis' : 'Guest' }}
                        </span>
                    @endauth
                    
                    <!-- Language Switcher -->
                    <form method="POST" action="{{ route('language.switch') }}" class="inline">
                        @csrf
                        <input type="hidden" name="locale" value="{{ $locale === 'lv' ? 'en' : 'lv' }}">
                        <button type="submit" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">
                            {{ $locale === 'lv' ? '🇬🇧 EN' : '🇱🇻 LV' }}
                        </button>
                    </form>
                    
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                                {{ $locale === 'lv' ? 'Iziet' : 'Logout' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">{{ $locale === 'lv' ? 'Ieiet' : 'Login' }}</a>
                    @endauth
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
            <h1 class="text-3xl md:text-4xl font-bold mb-3 text-white">Laipni lūdzam Bibliotekā</h1>
            <p class="text-base md:text-lg text-gray-300">Tava Digitālā Bibliotēkas Pieredze</p>
        </div>
    </section>

    <!-- What We Do Section -->
    <section class="py-8 bg-gray-900">
        <div class="max-w-2xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-2 text-center text-purple-400">Ko Mēs Darām</h2>
            <p class="text-center text-gray-500 text-xs mb-6">Tava galvenā digitālās bibliotēkas platforma</p>
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-gray-800 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">📚 Pārlūko & Atklāj</h3>
                    <p class="text-gray-400 text-xs">Izpēti mūsu plašo katalogu ar meklēšanu un filtrēšanu.</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">🔒 Droša Grāmatu Izņemšana</h3>
                    <p class="text-gray-400 text-xs">Izņem grāmatas ar pārliecību, izmantojot drošu sistēmu.</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">📖 Veido Savu Bibliotēku</h3>
                    <p class="text-gray-400 text-xs">Izveido un pārvaldi savu personīgo grāmatu kolekciju.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Privacy Policy Section -->
    <section class="py-8 bg-black">
        <div class="max-w-2xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-2 text-center text-purple-400">Privātuma Politika</h2>
            <p class="text-center text-gray-500 text-xs mb-6">Tava privātums mums ir svarīgs</p>
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">📋 Informācija Ko Vācam</h3>
                    <p class="text-gray-400 text-xs">Vārds, e-pasts, pārlūkošanas aktivitāte un grāmatu preferences.</p>
                </div>
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">⚙️ Kā To Izmantojam</h3>
                    <p class="text-gray-400 text-xs">Apstrādājam izņemšanas, uzturām kontus, sūtām atjauninājumus un ieteikumus.</p>
                </div>
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">🔐 Drošība</h3>
                    <p class="text-gray-400 text-xs">Nozares standarta šifrēšana. Dati tiek droši apstrādāti.</p>
                </div>
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">🤝 Datu Koplietošana</h3>
                    <p class="text-gray-400 text-xs">Mēs nepārdodam tavus datus. Dati tiek dalīti tikai ar uzticamiem pakalpojumu sniedzējiem.</p>
                </div>
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">✅ Tavas Tiesības</h3>
                    <p class="text-gray-400 text-xs">Piekļūsti, atjaunini vai dzēs savus datus jebkurā laikā sadaļā Par Mums.</p>
                </div>
            </div>
            <p class="text-xs text-gray-600 mt-6 text-center">Pēdējo reizi atjaunināts: 2026. gada 13. janvāris</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black border-t border-purple-500/30 py-4">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p class="text-gray-500 text-xs">&copy; 2026 Biblioteka. Visas tiesības aizsargātas.</p>
        </div>
    </footer>
</body>
</html>
