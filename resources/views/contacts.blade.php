<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kontakti - Bibliotēkas Pārvaldības Sistēma</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-md rounded-lg p-4 mb-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Bibliotēkas Pārvaldības Sistēma</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('library') }}" class="text-blue-600 hover:text-blue-800">Sākums</a>
                    <a href="{{ route('books.create') }}" class="text-blue-600 hover:text-blue-800">Pievienot Grāmatas</a>
                    <a href="#search" class="text-blue-600 hover:text-blue-800">Meklēt</a>
                    <a href="{{ route('contacts') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Kontakti</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Iziet
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Contacts Content -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold mb-4">Sazinies Ar Mums</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium mb-2">Bibliotēkas Informācija</h3>
                    <p class="text-gray-600 mb-2">Adrese: Brīvības iela 123, Rīga, LV-1001</p>
                    <p class="text-gray-600 mb-2">Tālrunis: +371 12345678</p>
                    <p class="text-gray-600 mb-2">E-pasts: info@biblioteka.lv</p>
                    <p class="text-gray-600">Darba laiks: P-Pk 9:00-18:00, S 10:00-16:00</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium mb-2">Kontaktu Forma</h3>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vārds</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">E-pasts</label>
                            <input type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ziņojums</label>
                            <textarea rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Nosūtīt Ziņojumu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>