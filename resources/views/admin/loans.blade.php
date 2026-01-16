<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Admin Panel') }} - {{ __('Loans Management') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 min-h-screen text-white">
    <!-- Navigation -->
    <nav class="bg-black/30 backdrop-blur-lg border-b border-white/10 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent hover:from-purple-300 hover:to-pink-300 transition-all">
                    📚 Bibliot-ēka
                </a>
                
                <div class="flex items-center gap-6">
                    <a href="{{ url('/admin/loans') }}" class="text-purple-300 hover:text-white transition-colors font-semibold border-b-2 border-purple-400">
                        {{ __('Loans Management') }}
                    </a>
                    <a href="{{ url('/admin/users') }}" class="text-gray-300 hover:text-white transition-colors">
                        {{ __('User Management') }}
                    </a>
                    <a href="{{ url('/') }}" class="text-gray-300 hover:text-white transition-colors">
                        {{ __('Back to Site') }}
                    </a>
                    
                    <!-- Language Switcher -->
                    <div class="flex items-center gap-2 bg-white/10 rounded-full px-3 py-1">
                        <button onclick="switchLanguage('en')" class="lang-btn text-sm hover:text-purple-300 transition-colors" data-lang="en">
                            🇬🇧 EN
                        </button>
                        <span class="text-white/50">|</span>
                        <button onclick="switchLanguage('lv')" class="lang-btn text-sm hover:text-purple-300 transition-colors" data-lang="lv">
                            🇱🇻 LV
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold mb-8 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                🛡️ {{ __('Loans Management') }}
            </h1>

            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Loans Table -->
            <div class="bg-white/5 backdrop-blur-lg rounded-2xl border border-white/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('Book') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('Borrower') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('Loan Date') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('Due Date') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('Return Date') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($loans as $loan)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($loan->book->image)
                                                <img src="{{ asset('storage/' . $loan->book->image) }}" alt="{{ $loan->book->title }}" class="w-12 h-16 object-cover rounded">
                                            @else
                                                <div class="w-12 h-16 bg-purple-500/20 rounded flex items-center justify-center text-2xl">📖</div>
                                            @endif
                                            <div>
                                                <div class="font-semibold">{{ $loan->book->title }}</div>
                                                <div class="text-sm text-gray-400">{{ $loan->book->author }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="font-semibold">{{ $loan->user->name }}</div>
                                            <div class="text-sm text-gray-400">{{ $loan->user->email }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $loan->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $dueDate = $loan->created_at->addDays(14);
                                            $isOverdue = !$loan->returned_at && $dueDate->isPast();
                                        @endphp
                                        <span class="{{ $isOverdue ? 'text-red-400 font-bold' : 'text-gray-300' }}">
                                            {{ $dueDate->format('Y-m-d') }}
                                            @if($isOverdue)
                                                ⚠️
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($loan->returned_at)
                                            <span class="text-green-400">{{ $loan->returned_at->format('Y-m-d') }}</span>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($loan->returned_at)
                                            <span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm">
                                                ✓ {{ __('Returned') }}
                                            </span>
                                        @elseif($isOverdue)
                                            <span class="px-3 py-1 bg-red-500/20 text-red-300 rounded-full text-sm">
                                                ⚠️ {{ __('Overdue') }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-yellow-500/20 text-yellow-300 rounded-full text-sm">
                                                📖 {{ __('Active') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        {{ __('No loans found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-purple-500/20 backdrop-blur-lg rounded-xl border border-purple-500/30 p-6">
                    <div class="text-3xl font-bold">{{ $loans->whereNull('returned_at')->count() }}</div>
                    <div class="text-gray-300 mt-2">{{ __('Active Loans') }}</div>
                </div>
                <div class="bg-green-500/20 backdrop-blur-lg rounded-xl border border-green-500/30 p-6">
                    <div class="text-3xl font-bold">{{ $loans->whereNotNull('returned_at')->count() }}</div>
                    <div class="text-gray-300 mt-2">{{ __('Returned') }}</div>
                </div>
                <div class="bg-red-500/20 backdrop-blur-lg rounded-xl border border-red-500/30 p-6">
                    <div class="text-3xl font-bold">
                        {{ $loans->filter(function($loan) {
                            return !$loan->returned_at && $loan->created_at->addDays(14)->isPast();
                        })->count() }}
                    </div>
                    <div class="text-gray-300 mt-2">{{ __('Overdue') }}</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const TRANSLATIONS = {!! json_encode(__('translations')) !!};
        
        function switchLanguage(lang) {
            fetch('/locale/' + lang, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                location.reload();
            });
        }

        // Update active language button
        const currentLang = '{{ app()->getLocale() }}';
        document.querySelectorAll('.lang-btn').forEach(btn => {
            if (btn.dataset.lang === currentLang) {
                btn.classList.add('text-purple-300', 'font-bold');
            }
        });
    </script>
</body>
</html>
