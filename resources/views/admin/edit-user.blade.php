<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Edit User') }} - {{ $user->name }}</title>
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
                    <a href="{{ url('/admin/users') }}" class="text-gray-300 hover:text-white transition-colors">
                        ← {{ __('Back to Users') }}
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
        <div class="max-w-2xl mx-auto">
            <h1 class="text-4xl font-bold mb-8 bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                ✏️ {{ __('Edit User') }}
            </h1>

            <div class="bg-white/5 backdrop-blur-lg rounded-2xl border border-white/10 p-8">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-purple-300 mb-2">
                            {{ __('Name') }}
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 transition-colors">
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-purple-300 mb-2">
                            {{ __('Email') }}
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 transition-colors">
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="mb-6">
                        <label for="role" class="block text-sm font-semibold text-purple-300 mb-2">
                            {{ __('Role') }}
                        </label>
                        <select id="role" name="role" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:outline-none focus:border-purple-500 transition-colors">
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>🛡️ {{ __('Administrator') }}</option>
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>👤 {{ __('User') }}</option>
                            <option value="guest" {{ $user->role === 'guest' ? 'selected' : '' }}>👁️ {{ __('Guest') }}</option>
                        </select>
                        @error('role')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password (Optional) -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-semibold text-purple-300 mb-2">
                            {{ __('New Password') }} <span class="text-gray-400 font-normal">({{ __('Leave empty to keep current') }})</span>
                        </label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 transition-colors">
                        @error('password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="mb-8">
                        <label for="password_confirmation" class="block text-sm font-semibold text-purple-300 mb-2">
                            {{ __('Confirm Password') }}
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 transition-colors">
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-lg transition-all transform hover:scale-105">
                            💾 {{ __('Save Changes') }}
                        </button>
                        <a href="{{ route('admin.users') }}" class="flex-1 bg-white/5 hover:bg-white/10 text-white font-bold py-3 px-6 rounded-lg transition-all text-center">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
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
