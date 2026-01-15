<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Par Mums - Biblioteka</title>
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
    <!-- Navigācija -->
    <nav class="bg-black border-b border-purple-500">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-purple-400">Biblioteka</h1>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">Sākums</a>
                    <a href="/search" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">Meklēt</a>
                    <a href="{{ url('/books/create') }}" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">Pievienot Grāmatas</a>
                    <a href="/about" class="text-gray-300 hover:text-purple-400 text-sm font-medium transition-colors">Par Mums</a>
                    <a href="{{ route('login') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">Ieiet</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Paziņojums -->
    <div id="notification" class="fixed top-4 right-4 bg-green-600 dark:bg-green-600 text-white px-4 py-2 rounded shadow-lg hidden z-50">
        <p id="notificationMessage"></p>
    </div>

    <!-- Galvenā Sadaļa -->
    <section class="bg-gradient-to-b from-purple-900 to-black py-12">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-3 text-white">Par Biblioteku</h1>
            <p class="text-base md:text-lg text-gray-300">Tava Digitālā Bibliotēkas Pieredze</p>
        </div>
    </section>

    <!-- Par Mums Sadaļa -->
    <section class="py-8 bg-gray-900">
        <div class="max-w-2xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-2 text-center text-purple-400">Kas Mēs Esam</h2>
            <p class="text-center text-gray-500 text-xs mb-6">Tava galvenā digitālās bibliotēkas platforma</p>
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-gray-800 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">📚 Plaša Kolekcija</h3>
                    <p class="text-gray-400 text-xs">Tūkstošiem grāmatu visos žanros un kategorijās tavā rīcībā.</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">⚡ Ātra Meklēšana</h3>
                    <p class="text-gray-400 text-xs">Atrodi grāmatas nekavējoties ar mūsu jaudīgo meklēšanas sistēmu un filtriem.</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">✅ Viegla Pārvaldība</h3>
                    <p class="text-gray-400 text-xs">Izņem un izseko aizņēmumus ar mūsu lietotājam draudzīgo saskarni.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontaktu Sadaļa -->
    <section class="py-8 bg-black">
        <div class="max-w-2xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-2 text-center text-purple-400">Sazinies Ar Mums</h2>
            <p class="text-center text-gray-500 text-xs mb-6">Ir jautājumi? Mēs labprāt dzirdētu no tevis</p>
            <div class="grid grid-cols-1 gap-4">
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">📧 E-pasts</h3>
                    <p class="text-gray-400 text-xs">atbalsts@biblioteka.lv</p>
                </div>
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">📞 Tālrunis</h3>
                    <p class="text-gray-400 text-xs">+371 12345678</p>
                </div>
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">📍 Adrese</h3>
                    <p class="text-gray-400 text-xs">Brīvības iela 123, Rīga, LV-1001</p>
                </div>
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-semibold text-purple-400 mb-1">�� Darba Laiks</h3>
                    <p class="text-gray-400 text-xs">Pirmdiena - Piektdiena: 9:00 - 18:00</p>
                </div>
            </div>
            <p class="text-xs text-gray-600 mt-6 text-center">Mēs parasti atbildam 24 stundu laikā</p>
        </div>
    </section>

    <!-- Kājene -->
    <footer class="bg-black border-t border-purple-500/30 py-4">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p class="text-gray-500 text-xs">&copy; 2026 Biblioteka. Visas tiesības aizsargātas.</p>
        </div>
    </footer>
</body>
</html>
