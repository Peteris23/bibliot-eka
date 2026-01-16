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
    <title>{{ $locale === 'lv' ? 'Profils' : 'Profile' }} - {{ t('Library Management System', $translations) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-md rounded-lg p-4 mb-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">{{ t('Library Management System', $translations) }}</h1>
                <div class="flex space-x-4 items-center">
                    <a href="{{ route('library') }}" class="text-blue-600 hover:text-blue-800">{{ t('Home', $translations) }}</a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('books.create') }}" class="text-blue-600 hover:text-blue-800">{{ t('Add Books', $translations) }}</a>
                    @endif
                    <a href="{{ route('contacts') }}" class="text-blue-600 hover:text-blue-800">{{ t('Contacts', $translations) }}</a>
                    
                    <!-- User Info -->
                    <div class="flex items-center bg-gray-100 px-3 py-2 rounded-md">
                        <span class="text-gray-700 text-sm font-medium">
                            👤 {{ auth()->user()->name }}
                            @if(auth()->user()->isAdmin())
                                <span class="ml-1 bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded">Admin</span>
                            @endif
                        </span>
                    </div>
                    
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

        <!-- Profile Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- User Information Card -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-purple-400 to-blue-500 rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                        <p class="text-gray-600 text-sm">{{ auth()->user()->email }}</p>
                        @if(auth()->user()->isAdmin())
                            <span class="inline-block mt-2 bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full">
                                🛡️ {{ $locale === 'lv' ? 'Administrators' : 'Administrator' }}
                            </span>
                        @else
                            <span class="inline-block mt-2 bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                                📚 {{ $locale === 'lv' ? 'Lietotājs' : 'User' }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="border-t pt-4">
                        <h3 class="font-semibold text-gray-700 mb-3">{{ $locale === 'lv' ? 'Konta Informācija' : 'Account Information' }}</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $locale === 'lv' ? 'Reģistrēts' : 'Member Since' }}:</span>
                                <span class="font-medium">{{ auth()->user()->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $locale === 'lv' ? 'Kopā Aizņēmumi' : 'Total Loans' }}:</span>
                                <span class="font-medium" id="totalLoans">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $locale === 'lv' ? 'Aktīvie Aizņēmumi' : 'Active Loans' }}:</span>
                                <span class="font-medium text-blue-600" id="activeLoans">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan History -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">{{ $locale === 'lv' ? 'Mani Aizņēmumi' : 'My Loans' }}</h2>
                    
                    <!-- Active Loans -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-blue-600 mb-3">{{ $locale === 'lv' ? '📖 Aktīvie Aizņēmumi' : '📖 Active Loans' }}</h3>
                        <div id="activeLoansContainer" class="space-y-3">
                            <p class="text-gray-500 text-center py-4">{{ $locale === 'lv' ? 'Ielādē...' : 'Loading...' }}</p>
                        </div>
                    </div>

                    <!-- Loan History -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-600 mb-3">{{ $locale === 'lv' ? '📚 Aizņēmumu Vēsture' : '📚 Loan History' }}</h3>
                        <div id="loanHistoryContainer" class="space-y-3">
                            <p class="text-gray-500 text-center py-4">{{ $locale === 'lv' ? 'Ielādē...' : 'Loading...' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '/api';
        const LOCALE = '{{ $locale }}';

        async function loadProfile() {
            try {
                const [loansResponse, booksResponse] = await Promise.all([
                    fetch(`${API_BASE}/loans/my-loans`, { credentials: 'same-origin' }),
                    fetch(`${API_BASE}/books`, { credentials: 'same-origin' })
                ]);

                const loans = await loansResponse.json();
                const books = await booksResponse.json();

                // Create a map of books for easy lookup
                const booksMap = {};
                books.forEach(book => {
                    booksMap[book.id] = book;
                });

                // Separate active and returned loans
                const activeLoans = loans.filter(loan => !loan.return_date);
                const returnedLoans = loans.filter(loan => loan.return_date);

                // Update statistics
                document.getElementById('totalLoans').textContent = loans.length;
                document.getElementById('activeLoans').textContent = activeLoans.length;

                // Display active loans
                displayLoans(activeLoans, booksMap, 'activeLoansContainer', true);
                
                // Display loan history
                displayLoans(returnedLoans, booksMap, 'loanHistoryContainer', false);
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        function displayLoans(loans, booksMap, containerId, isActive) {
            const container = document.getElementById(containerId);
            
            if (loans.length === 0) {
                container.innerHTML = `<p class="text-gray-500 text-center py-4">${LOCALE === 'lv' ? 'Nav aizņēmumu' : 'No loans'}</p>`;
                return;
            }

            container.innerHTML = loans.map(loan => {
                const book = booksMap[loan.book_id];
                if (!book) return '';

                const loanDate = new Date(loan.loan_date).toLocaleDateString();
                const returnDate = loan.return_date ? new Date(loan.return_date).toLocaleDateString() : null;
                const daysAgo = Math.floor((new Date() - new Date(loan.loan_date)) / (1000 * 60 * 60 * 24));

                return `
                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow ${isActive ? 'bg-blue-50 border-blue-200' : 'bg-gray-50'}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">${book.title}</h4>
                                <p class="text-sm text-gray-600">${LOCALE === 'lv' ? 'Autors' : 'Author'}: ${book.author}</p>
                                <p class="text-sm text-gray-600">ISBN: ${book.isbn}</p>
                            </div>
                            ${isActive ? `
                                <button onclick="returnBook(${book.id})" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    ${LOCALE === 'lv' ? 'Atgriezt' : 'Return'}
                                </button>
                            ` : ''}
                        </div>
                        <div class="mt-3 text-xs text-gray-500 flex justify-between">
                            <span>📅 ${LOCALE === 'lv' ? 'Izņemts' : 'Loaned'}: ${loanDate}</span>
                            ${returnDate ? 
                                `<span>✅ ${LOCALE === 'lv' ? 'Atgriezts' : 'Returned'}: ${returnDate}</span>` :
                                `<span class="${daysAgo > 14 ? 'text-red-600 font-semibold' : ''}">${daysAgo} ${LOCALE === 'lv' ? 'dienas' : 'days'}</span>`
                            }
                        </div>
                    </div>
                `;
            }).join('');
        }

        async function returnBook(bookId) {
            try {
                const loansResponse = await fetch(`${API_BASE}/loans/my-loans`);
                const loans = await loansResponse.json();

                const loan = loans.find(l => l.book_id === bookId && !l.return_date);
                if (!loan) {
                    alert(LOCALE === 'lv' ? 'Nav atrasts aktīvs aizņēmums' : 'No active loan found');
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

                if (response.ok) {
                    alert(LOCALE === 'lv' ? 'Grāmata veiksmīgi atgriezta!' : 'Book returned successfully!');
                    loadProfile();
                } else {
                    alert(LOCALE === 'lv' ? 'Kļūda atgriežot grāmatu' : 'Error returning book');
                }
            } catch (error) {
                console.error('Error returning book:', error);
                alert(LOCALE === 'lv' ? 'Kļūda atgriežot grāmatu' : 'Error returning book');
            }
        }

        // Load profile on page load
        loadProfile();
    </script>
</body>
</html>
