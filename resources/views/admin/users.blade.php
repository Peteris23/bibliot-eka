<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Admin Panel') }} - {{ __('User Management') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black min-h-screen text-white">
    <!-- Navigation -->
    <nav class="bg-black border-b border-purple-500 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent hover:from-purple-300 hover:to-pink-300 transition-all">
                    📚 Bibliot-ēka
                </a>
                
                <div class="flex items-center gap-6">
                    <a href="{{ url('/admin/loans') }}" class="text-gray-300 hover:text-white transition-colors">
                        {{ __('Loans Management') }}
                    </a>
                    <a href="{{ url('/admin/users') }}" class="text-purple-300 hover:text-white transition-colors font-semibold border-b-2 border-purple-400">
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
                👥 {{ __('User Management') }}
            </h1>

            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Users Table -->
            <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('User') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('Email') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('Role') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('Total Loans') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('Member Since') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-purple-300">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-800 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="font-semibold">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-300">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->isAdmin())
                                            <span class="px-3 py-1 bg-red-500/20 text-red-300 rounded-full text-sm">
                                                🛡️ {{ __('Administrator') }}
                                            </span>
                                        @elseif($user->isUser())
                                            <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm">
                                                👤 {{ __('User') }}
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-500/20 text-gray-300 rounded-full text-sm">
                                                👁️ {{ __('Guest') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-300">
                                        {{ $user->loans_count }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">
                                        {{ $user->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="px-4 py-2 bg-blue-500/20 text-blue-300 rounded-lg hover:bg-blue-500/30 transition-colors text-sm">
                                                ✏️ {{ __('Edit') }}
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-4 py-2 bg-red-500/20 text-red-300 rounded-lg hover:bg-red-500/30 transition-colors text-sm">
                                                        🗑️ {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        {{ __('No users found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-gray-900 rounded-xl border border-red-500 p-6">
                    <div class="text-3xl font-bold">{{ $users->where('role', 'admin')->count() }}</div>
                    <div class="text-gray-300 mt-2">🛡️ {{ __('Administrators') }}</div>
                </div>
                <div class="bg-gray-900 rounded-xl border border-blue-500 p-6">
                    <div class="text-3xl font-bold">{{ $users->where('role', 'user')->count() }}</div>
                    <div class="text-gray-300 mt-2">👤 {{ __('Users') }}</div>
                </div>
                <div class="bg-gray-900 rounded-xl border border-gray-500 p-6">
                    <div class="text-3xl font-bold">{{ $users->where('role', 'guest')->count() }}</div>
                    <div class="text-gray-300 mt-2">👁️ {{ __('Guests') }}</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const TRANSLATIONS = {!! json_encode(__('translations')) !!};
        
        function switchLanguage(lang) {
            fetch('/language', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ locale: lang })
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
