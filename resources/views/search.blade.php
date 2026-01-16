<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meklēt Grāmatas - Biblioteka</title>
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
                <div class="flex space-x-4">
                    <a href="/" class="text-gray-300 hover:text-purple-400 text-sm font-medium">Sākums</a>
                    <a href="/search" class="text-gray-300 hover:text-purple-400 text-sm font-medium">Meklēt</a>
                    <a href="{{ url('/books/create') }}" class="text-gray-300 hover:text-purple-400 text-sm font-medium">Pievienot Grāmatas</a>
                    <a href="/about" class="text-gray-300 hover:text-purple-400 text-sm font-medium">Par Mums</a>
                    <a href="{{ route('login') }}" class="bg-purple-600 text-white px-4 py-1 rounded text-sm font-medium hover:bg-purple-700">Ieiet</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Meklēšanas Sadaļa -->
    <section class="bg-gradient-to-b from-purple-900 to-black py-12">
        <div class="max-w-6xl mx-auto px-4">
            <h1 class="text-3xl md:text-5xl font-bold mb-3 text-white text-center">Meklēt Grāmatas</h1>
            <p class="text-lg md:text-xl mb-6 text-gray-300 text-center">Atrodi savu nākamo lielisku lasāmvielu</p>
            <div class="max-w-md mx-auto">
                <form id="searchForm" class="flex">
                    <input type="text" id="searchInput" placeholder="Meklēt pēc nosaukuma, autora vai žanra..." class="flex-1 px-3 py-2 bg-gray-800 border border-purple-500 rounded-l text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-r hover:bg-purple-700 font-medium">Meklēt</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Meklēšanas Rezultāti -->
    <section class="py-8 bg-black">
        <div class="max-w-6xl mx-auto px-4">
            <div id="resultsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
            <div id="noResults" class="text-center text-gray-400 py-8 hidden">
                <p class="text-xl">Grāmatas nav atrastas. Mēģini citu meklēšanas vaicājumu.</p>
            </div>
        </div>
    </section>

    <!-- Visas Grāmatas -->
    <section class="py-8 bg-gray-900">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6 text-center text-purple-400">Visas Grāmatas</h2>
            <div id="allBooks" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Grāmatas tiks ielādētas šeit -->
            </div>
        </div>
    </section>

    <!-- Kājene -->
    <footer class="bg-black border-t border-purple-500 py-4">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">&copy; 2026 Biblioteka</p>
        </div>
    </footer>

    <script>
        const API_BASE = '{{ url("/") }}/api';
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const resultsContainer = document.getElementById('resultsContainer');
        const allBooksContainer = document.getElementById('allBooks');
        const noResults = document.getElementById('noResults');

        // Ielādēt visas grāmatas lapas ielādes laikā
        async function loadAllBooks() {
            try {
                const response = await fetch(`${API_BASE}/books`);
                const books = await response.json();
                displayBooks(books, allBooksContainer);
            } catch (error) {
                console.error('Kļūda ielādējot grāmatas:', error);
                allBooksContainer.innerHTML = '<p class="text-center text-gray-400">Kļūda ielādējot grāmatas.</p>';
            }
        }

        function displayBooks(books, container) {
            container.innerHTML = '';
            if (books.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-400">Grāmatas nav pieejamas.</p>';
            } else {
                books.forEach(book => {
                    const bookCard = document.createElement('div');
                    bookCard.className = 'bg-gray-800 rounded-lg p-4 border border-purple-500';
                    const imageUrl = book.image ? `/storage/${book.image}` : 'https://via.placeholder.com/150x200?text=Nav+Attela';
                    
                    bookCard.innerHTML = `
                        <img src="${imageUrl}" alt="${book.title}" class="w-full h-48 object-cover rounded-md mb-3" onerror="this.src='https://via.placeholder.com/150x200?text=Nav+Attela'">
                        <h3 class="text-lg font-bold text-purple-400 mb-1">${book.title}</h3>
                        <p class="text-sm text-gray-300 mb-1">Autors: ${book.author}</p>
                        ${book.genre ? `<p class="text-xs text-purple-300 mb-1">Žanrs: ${book.genre}</p>` : ''}
                        <p class="text-xs text-gray-400">ISBN: ${book.isbn} | Gads: ${book.year}</p>
                    `;
                    container.appendChild(bookCard);
                });
            }
        }

        // Meklēšanas forma
        searchForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const query = searchInput.value.trim();
            
            if (!query) {
                alert('Lūdzu ievadi meklēšanas vaicājumu');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/books/search?q=${encodeURIComponent(query)}`);
                const books = await response.json();
                
                resultsContainer.innerHTML = '';
                noResults.classList.add('hidden');
                
                if (books.length === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    displayBooks(books, resultsContainer);
                }
            } catch (error) {
                console.error('Kļūda meklējot:', error);
                alert('Kļūda meklējot grāmatas');
            }
        });

        // Ielādēt grāmatas
        loadAllBooks();
    </script>
</body>
</html>
