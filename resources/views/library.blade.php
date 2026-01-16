@php
    $locale = session('locale', 'lv');
    $translations = json_decode(file_get_contents(resource_path("lang/{$locale}.json")), true);
    function t($key, $translations) {
        return $translations[$key] ?? $key;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ t('Library Management System', $translations) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Navigācijas Josla -->
        <nav class="bg-white shadow-md rounded-lg p-4 mb-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">{{ t('Library Management System', $translations) }}</h1>
                <div class="flex space-x-4 items-center">
                    <a href="{{ route('library') }}" class="text-blue-600 hover:text-blue-800 font-semibold">{{ t('Home', $translations) }}</a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('books.create') }}" class="text-blue-600 hover:text-blue-800">{{ t('Add Books', $translations) }}</a>
                    @endif
                    <a href="#search" class="text-blue-600 hover:text-blue-800">{{ t('Search', $translations) }}</a>
                    <a href="{{ route('contacts') }}" class="text-blue-600 hover:text-blue-800">{{ t('Contacts', $translations) }}</a>
                    
                    <!-- Language Switcher -->
                    <form method="POST" action="{{ route('language.switch') }}" class="inline">
                        @csrf
                        <input type="hidden" name="locale" value="{{ $locale === 'lv' ? 'en' : 'lv' }}">
                        <button type="submit" class="bg-gray-200 text-gray-700 px-3 py-2 rounded-md hover:bg-gray-300 text-sm">
                            {{ $locale === 'lv' ? '🇬🇧 EN' : '🇱🇻 LV' }}
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            {{ t('Logout', $translations) }}
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Grāmatas Pievienošanas Forma -->
        @if(auth()->user()->isAdmin())
        <div id="add-book" class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ t('Add New Book', $translations) }}</h2>
            <form id="addBookForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('Title', $translations) }}</label>
                    <input type="text" id="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('Author', $translations) }}</label>
                    <input type="text" id="author" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">ISBN</label>
                    <input type="text" id="isbn" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('Year', $translations) }}</label>
                    <input type="number" id="year" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('Genre', $translations) }}</label>
                    <select id="genre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ t('Select genre', $translations) }}</option>
                        <option value="{{ t('Fiction', $translations) }}">{{ t('Fiction', $translations) }}</option>
                        <option value="{{ t('Non-Fiction', $translations) }}">{{ t('Non-Fiction', $translations) }}</option>
                        <option value="{{ t('Mystery', $translations) }}">{{ t('Mystery', $translations) }}</option>
                        <option value="{{ t('Romance', $translations) }}">{{ t('Romance', $translations) }}</option>
                        <option value="{{ t('Science Fiction', $translations) }}">{{ t('Science Fiction', $translations) }}</option>
                        <option value="{{ t('Fantasy', $translations) }}">{{ t('Fantasy', $translations) }}</option>
                        <option value="{{ t('Biography', $translations) }}">{{ t('Biography', $translations) }}</option>
                        <option value="{{ t('History', $translations) }}">{{ t('History', $translations) }}</option>
                        <option value="{{ t('Self-Help', $translations) }}">{{ t('Self-Help', $translations) }}</option>
                        <option value="{{ t('Children', $translations) }}">{{ t('Children', $translations) }}</option>
                        <option value="{{ t('Other', $translations) }}">{{ t('Other', $translations) }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('Image', $translations) }}</label>
                    <input type="file" id="image" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">{{ t('Description', $translations) }}</label>
                    <textarea id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        {{ t('Add Book', $translations) }}
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Grāmatu Meklēšana -->
        <div id="search" class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ t('Search Books', $translations) }}</h2>
            <div class="flex gap-4 mb-4">
                <input type="text" id="searchQuery" placeholder="{{ t('Search by title, author or genre...', $translations) }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <select id="searchType" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="title">{{ t('Title', $translations) }}</option>
                    <option value="author">{{ t('Author', $translations) }}</option>
                    <option value="genre">{{ t('Genre', $translations) }}</option>
                </select>
                <button id="searchBtn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    {{ t('Search', $translations) }}
                </button>
            </div>
        </div>

        <!-- Grāmatu Saraksts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">{{ t('All Books', $translations) }}</h2>
                <button id="loadBooksBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{ t('Refresh List', $translations) }}
                </button>
            </div>
            <div id="booksList" class="space-y-4">
                <!-- Grāmatas tiks ielādētas šeit -->
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '/api';
        const IS_ADMIN = {{ auth()->user()->isAdmin() ? 'true' : 'false' }};
        const LOCALE = '{{ $locale }}';
        const TRANSLATIONS = @json($translations);

        // Ielādēt visas grāmatas
        async function loadBooks() {
            try {
                const booksResponse = await fetch(`${API_BASE}/books`, { credentials: 'same-origin' });
                const books = await booksResponse.json();

                let loans = [];
                try {
                    const loansResponse = await fetch(`${API_BASE}/loans/my-loans`, { credentials: 'same-origin' });
                    if (loansResponse.ok) {
                        loans = await loansResponse.json();
                    }
                } catch (loansError) {
                    console.warn('Nevarēja ielādēt lietotāja aizņēmumus:', loansError);
                }

                displayBooks(books, loans);
            } catch (error) {
                console.error('Kļūda ielādējot grāmatas:', error);
                alert('Kļūda ielādējot grāmatas');
            }
        }

        // Attēlot grāmatas sarakstā
        function displayBooks(books, userLoans) {
            const booksList = document.getElementById('booksList');
            booksList.innerHTML = '';

            if (books.length === 0) {
                booksList.innerHTML = '<p class="text-gray-500 text-center py-8">Bibliotēkā vēl nav nevienas grāmatas.</p>';
                return;
            }

            books.forEach(book => {
                const bookElement = document.createElement('div');
                bookElement.className = 'bg-white rounded-lg shadow-md p-4 inline-block m-2 w-64';
                const imageUrl = book.image ? `/storage/${book.image}` : 'https://via.placeholder.com/150x200?text=Nav+Attela';

                // Pārbaudīt vai lietotājam ir aizņemta šī grāmata
                const userLoan = userLoans.find(loan => loan.book_id === book.id && !loan.return_date);
                const isLoanedByUser = !!userLoan;

                let statusText, statusClass, actionButton;
                if (isLoanedByUser) {
                    statusText = TRANSLATIONS['Loaned by you'];
                    statusClass = 'text-blue-600';
                    actionButton = `<button onclick="returnBook(${book.id})" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 flex-1">
                        ${TRANSLATIONS['Return']}
                    </button>`;
                } else if (!book.available) {
                    statusText = TRANSLATIONS['Not Available'];
                    statusClass = 'text-red-600';
                    actionButton = '';
                } else {
                    statusText = TRANSLATIONS['Available'];
                    statusClass = 'text-green-600';
                    actionButton = `<button onclick="loanBook(${book.id})" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 flex-1">
                        ${TRANSLATIONS['Loan']}
                    </button>`;
                }

                bookElement.innerHTML = `
                    <img src="${imageUrl}" alt="${book.title}" class="w-full h-48 object-cover rounded-md mb-4" onerror="this.src='https://via.placeholder.com/150x200?text=No+Image'">
                    <h3 class="text-lg font-semibold mb-2">${book.title}</h3>
                    <p class="text-gray-600 mb-1">${TRANSLATIONS['Author']}: ${book.author}</p>
                    ${book.genre ? `<p class="text-sm text-blue-600 mb-1">${TRANSLATIONS['Genre']}: ${book.genre}</p>` : ''}
                    <p class="text-sm text-gray-500 mb-2">ISBN: ${book.isbn} | ${TRANSLATIONS['Year']}: ${book.year}</p>
                    ${book.description ? `<p class="text-sm mb-2">${book.description}</p>` : ''}
                    <p class="text-sm mb-2 ${statusClass}">
                        ${statusText}
                    </p>
                    <div class="flex gap-2 mb-2">
                        ${actionButton}
                        ${IS_ADMIN ? `<button onclick="deleteBook('${book.isbn}')" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 ${actionButton ? 'flex-1' : 'w-full'}">
                            ${TRANSLATIONS['Delete']}
                        </button>` : ''}
                    </div>
                `;
                booksList.appendChild(bookElement);
            });
        }

        // Pievienot jaunu grāmatu
        document.getElementById('addBookForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('title', document.getElementById('title').value);
            formData.append('author', document.getElementById('author').value);
            formData.append('isbn', document.getElementById('isbn').value);
            formData.append('year', parseInt(document.getElementById('year').value));
            formData.append('genre', document.getElementById('genre').value);
            formData.append('description', document.getElementById('description').value);
            const imageInput = document.getElementById('image');
            if (imageInput.files[0]) {
                formData.append('image', imageInput.files[0]);
            }

            try {
                const response = await fetch(`${API_BASE}/books`, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    alert('Grāmata veiksmīgi pievienota!');
                    document.getElementById('addBookForm').reset();
                    loadBooks();
                } else {
                    const error = await response.json();
                    alert('Kļūda pievienojot grāmatu: ' + JSON.stringify(error));
                }
            } catch (error) {
                console.error('Kļūda pievienojot grāmatu:', error);
                alert('Kļūda pievienojot grāmatu');
            }
        });

        // Meklēt grāmatas
        document.getElementById('searchBtn').addEventListener('click', async () => {
            const query = document.getElementById('searchQuery').value;
            const type = document.getElementById('searchType').value;

            if (!query.trim()) {
                alert('Lūdzu ievadi meklēšanas vaicājumu');
                return;
            }

            try {
                const [booksResponse, loansResponse] = await Promise.all([
                    fetch(`${API_BASE}/books/search?q=${encodeURIComponent(query)}&type=${type}`),
                    fetch(`${API_BASE}/loans/my-loans`)
                ]);
                const books = await booksResponse.json();
                const loans = await loansResponse.json();
                displayBooks(books, loans);
            } catch (error) {
                console.error('Kļūda meklējot grāmatas:', error);
                alert('Kļūda meklējot grāmatas');
            }
        });

        // Dzēst grāmatu
        async function deleteBook(isbn) {
            if (!confirm('Vai tiešām vēlies dzēst šo grāmatu?')) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/books/${isbn}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    alert('Grāmata veiksmīgi dzēsta!');
                    loadBooks();
                } else {
                    alert('Kļūda dzēšot grāmatu');
                }
            } catch (error) {
                console.error('Kļūda dzēšot grāmatu:', error);
                alert('Kļūda dzēšot grāmatu');
            }
        }

        // Izņemt grāmatu
        async function loanBook(bookId) {
            try {
                const response = await fetch(`${API_BASE}/loans/loan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ book_id: bookId })
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Grāmata veiksmīgi izņemta!');
                    loadBooks();
                } else {
                    alert('Kļūda izņemot grāmatu: ' + result.message);
                }
            } catch (error) {
                console.error('Kļūda izņemot grāmatu:', error);
                alert('Kļūda izņemot grāmatu');
            }
        }

        // Atgriezt grāmatu
        async function returnBook(bookId) {
            try {
                // Vispirms iegūt lietotāja aizņēmumus
                const loansResponse = await fetch(`${API_BASE}/loans/my-loans`);
                const loans = await loansResponse.json();

                const loan = loans.find(l => l.book_id === bookId && !l.return_date);
                if (!loan) {
                    alert('Nav atrasts aktīvs aizņēmums šai grāmatai');
                    return;
                }

                const response = await fetch(`${API_BASE}/loans/return`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ loan_id: loan.id })
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Grāmata veiksmīgi atgriezta!');
                    loadBooks();
                } else {
                    alert('Kļūda atgriežot grāmatu: ' + result.message);
                }
            } catch (error) {
                console.error('Kļūda atgriežot grāmatu:', error);
                alert('Kļūda atgriežot grāmatu');
            }
        }

        // Ielādēt grāmatas lapas ielādes laikā
        document.getElementById('loadBooksBtn').addEventListener('click', loadBooks);
        loadBooks();
    </script>
</body>
</html>
