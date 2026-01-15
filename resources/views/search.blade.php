<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Books - Biblioteka</title>
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
                <div class="flex space-x-4">
                    <a href="/" class="text-gray-300 hover:text-purple-400 text-sm font-medium">Home</a>
                    <a href="/search" class="text-gray-300 hover:text-purple-400 text-sm font-medium">Search</a>
                    <a href="{{ url('/books/create') }}" class="text-gray-300 hover:text-purple-400 text-sm font-medium">Create Books</a>
                    <a href="/about" class="text-gray-300 hover:text-purple-400 text-sm font-medium">About Us</a>
                    <a href="{{ route('login') }}" class="bg-purple-600 text-white px-4 py-1 rounded text-sm font-medium hover:bg-purple-700">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Search Section -->
    <section class="bg-gradient-to-b from-purple-900 to-black py-12">
        <div class="max-w-6xl mx-auto px-4">
            <h1 class="text-3xl md:text-5xl font-bold mb-3 text-white text-center">Search Books</h1>
            <p class="text-lg md:text-xl mb-6 text-gray-300 text-center">Find your next great read</p>
            <div class="max-w-md mx-auto">
                <form id="searchForm" class="flex">
                    <input type="text" id="searchInput" placeholder="Search by title, author, or genre..." class="flex-1 px-3 py-2 bg-gray-800 border border-purple-500 rounded-l text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-r hover:bg-purple-700 font-medium">Search</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Search Results -->
    <section class="py-8 bg-black">
        <div class="max-w-6xl mx-auto px-4">
            <div id="resultsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
            <div id="noResults" class="text-center text-gray-400 py-8 hidden">
                <p class="text-xl">No books found. Try a different search term.</p>
            </div>
        </div>
    </section>

    <!-- All Books -->
    <section class="py-8 bg-gray-900">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6 text-center text-purple-400">All Books</h2>
            <div id="allBooks" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Books will be loaded here -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black border-t border-purple-500 py-4">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">&copy; 2024 Biblioteka</p>
        </div>
    </footer>

    <script>
        const API_BASE = '{{ url("/") }}/api';
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const resultsContainer = document.getElementById('resultsContainer');
        const allBooksContainer = document.getElementById('allBooks');
        const noResults = document.getElementById('noResults');

        // Load all books on page load
        async function loadAllBooks() {
            try {
                const response = await fetch(`${API_BASE}/books/search`);
                const books = await response.json();
                displayBooks(books, allBooksContainer);
            } catch (error) {
                console.error('Error loading books:', error);
                allBooksContainer.innerHTML = '<p class="text-center text-gray-400">Error loading books.</p>';
            }
        }

        function displayBooks(books, container) {
            container.innerHTML = '';
            if (books.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-400">No books available.</p>';
            } else {
                books.forEach(book => {
                    const bookElement = document.createElement('div');
                    bookElement.className = 'bg-gray-800 p-4 rounded border border-purple-500';
                    bookElement.innerHTML = `
                        ${book.image ? `<img src="/storage/${book.image}" alt="${book.title}" class="w-full h-32 object-cover rounded mb-2">` : ''}
                        <h3 class="font-semibold text-purple-400 mb-1">${book.title}</h3>
                        <p class="text-gray-300 text-sm mb-1">Author: ${book.author}</p>
                        <p class="text-gray-300 text-sm mb-1">Genre: ${book.genre || 'N/A'}</p>
                        <p class="text-gray-300 text-sm mb-2">ISBN: ${book.isbn}</p>
                        <p class="text-purple-300 text-sm font-semibold mb-2">$19.99</p>
                        <button class="buy-btn bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 w-full" data-title="${book.title}">Buy Now</button>
                    `;
                    container.appendChild(bookElement);
                });
            }
        }

        searchForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const query = searchInput.value.trim();
            if (!query) return;

            noResults.classList.add('hidden');

            try {
                const response = await fetch(`${API_BASE}/books/search?q=${encodeURIComponent(query)}`);
                const books = await response.json();

                if (books.length === 0) {
                    noResults.classList.remove('hidden');
                    resultsContainer.innerHTML = '';
                } else {
                    displayBooks(books, resultsContainer);
                }
            } catch (error) {
                console.error('Search error:', error);
                noResults.classList.remove('hidden');
            }
        });

        // Load all books on page load
        loadAllBooks();
    </script>
</body>
</html>
