<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Library Management System</h1>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Logout
                </button>
            </form>
        </div>

        <!-- Add Book Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Add New Book</h2>
            <form id="addBookForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Author</label>
                    <input type="text" id="author" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">ISBN</label>
                    <input type="text" id="isbn" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Year</label>
                    <input type="number" id="year" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Genre</label>
                    <input type="text" id="genre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Image</label>
                    <input type="file" id="image" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Add Book
                    </button>
                </div>
            </form>
        </div>

        <!-- Search Books -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Search Books</h2>
            <div class="flex gap-4 mb-4">
                <input type="text" id="searchQuery" placeholder="Search by title or author..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <select id="searchType" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="title">Title</option>
                    <option value="author">Author</option>
                </select>
                <button id="searchBtn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Search
                </button>
            </div>
        </div>

        <!-- Books List -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">All Books</h2>
                <button id="loadBooksBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Refresh List
                </button>
            </div>
            <div id="booksList" class="space-y-4">
                <!-- Books will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '/api';

        // Load all books
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
                    console.warn('Could not load user loans:', loansError);
                }

                displayBooks(books, loans);
            } catch (error) {
                console.error('Error loading books:', error);
                alert('Error loading books');
            }
        }

        // Display books in the list
        function displayBooks(books, userLoans) {
            const booksList = document.getElementById('booksList');
            booksList.innerHTML = '';

            if (books.length === 0) {
                booksList.innerHTML = '<p class="text-gray-500 text-center py-8">No books in the library yet.</p>';
                return;
            }

            books.forEach(book => {
                const bookElement = document.createElement('div');
                bookElement.className = 'bg-white rounded-lg shadow-md p-4 inline-block m-2 w-64';
                const imageUrl = book.image ? `/storage/${book.image}` : '/images/default-book.png';

                // Check if user has this book loaned
                const userLoan = userLoans.find(loan => loan.book_id === book.id && !loan.return_date);
                const isLoanedByUser = !!userLoan;

                let statusText, statusClass, actionButton;
                if (isLoanedByUser) {
                    statusText = 'Loaned by you';
                    statusClass = 'text-blue-600';
                    actionButton = `<button onclick="returnBook(${book.id})" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 flex-1">
                        Return
                    </button>`;
                } else if (!book.available) {
                    statusText = 'Not Available';
                    statusClass = 'text-red-600';
                    actionButton = '';
                } else {
                    statusText = 'Available';
                    statusClass = 'text-green-600';
                    actionButton = `<button onclick="loanBook(${book.id})" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 flex-1">
                        Loan
                    </button>`;
                }

                bookElement.innerHTML = `
                    <img src="${imageUrl}" alt="${book.title}" class="w-full h-48 object-cover rounded-md mb-4" onerror="this.src='/images/default-book.png'">
                    <h3 class="text-lg font-semibold mb-2">${book.title}</h3>
                    <p class="text-gray-600 mb-1">by ${book.author}</p>
                    ${book.genre ? `<p class="text-sm text-blue-600 mb-1">Genre: ${book.genre}</p>` : ''}
                    <p class="text-sm text-gray-500 mb-2">ISBN: ${book.isbn} | Year: ${book.year}</p>
                    ${book.description ? `<p class="text-sm mb-2">${book.description}</p>` : ''}
                    <p class="text-sm mb-2 ${statusClass}">
                        ${statusText}
                    </p>
                    <div class="flex gap-2 mb-2">
                        ${actionButton}
                        <button onclick="deleteBook('${book.isbn}')" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 ${actionButton ? 'flex-1' : 'w-full'}">
                            Delete
                        </button>
                    </div>
                `;
                booksList.appendChild(bookElement);
            });
        }

        // Add new book
        document.getElementById('addBookForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData();
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
                    body: formData
                });

                if (response.ok) {
                    alert('Book added successfully!');
                    document.getElementById('addBookForm').reset();
                    loadBooks();
                } else {
                    const error = await response.json();
                    alert('Error adding book: ' + JSON.stringify(error));
                }
            } catch (error) {
                console.error('Error adding book:', error);
                alert('Error adding book');
            }
        });

        // Search books
        document.getElementById('searchBtn').addEventListener('click', async () => {
            const query = document.getElementById('searchQuery').value;
            const type = document.getElementById('searchType').value;

            if (!query.trim()) {
                alert('Please enter a search query');
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
                console.error('Error searching books:', error);
                alert('Error searching books');
            }
        });

        // Delete book
        async function deleteBook(isbn) {
            if (!confirm('Are you sure you want to delete this book?')) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/books/${isbn}`, {
                    method: 'DELETE'
                });

                if (response.ok) {
                    alert('Book deleted successfully!');
                    loadBooks();
                } else {
                    alert('Error deleting book');
                }
            } catch (error) {
                console.error('Error deleting book:', error);
                alert('Error deleting book');
            }
        }

        // Loan book
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
                    alert('Book loaned successfully!');
                    loadBooks();
                } else {
                    alert('Error loaning book: ' + result.message);
                }
            } catch (error) {
                console.error('Error loaning book:', error);
                alert('Error loaning book');
            }
        }

        // Return book
        async function returnBook(bookId) {
            try {
                // First get the user's loans to find the loan ID for this book
                const loansResponse = await fetch(`${API_BASE}/loans/my-loans`);
                const loans = await loansResponse.json();

                const loan = loans.find(l => l.book_id === bookId && !l.return_date);
                if (!loan) {
                    alert('No active loan found for this book');
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
                    alert('Book returned successfully!');
                    loadBooks();
                } else {
                    alert('Error returning book: ' + result.message);
                }
            } catch (error) {
                console.error('Error returning book:', error);
                alert('Error returning book');
            }
        }

        // Load books on page load
        document.getElementById('loadBooksBtn').addEventListener('click', loadBooks);
        loadBooks();
    </script>
</body>
</html>
