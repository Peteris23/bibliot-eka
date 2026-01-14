<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Book - Biblioteka</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-black text-white font-sans">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-purple-400">Add New Book</h1>

        @if(session('success'))
            <div class="bg-green-800 border border-green-500 text-green-200 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-800 border border-red-500 text-red-200 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="bg-gray-900 p-6 rounded border border-purple-500">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="title" class="block mb-2 text-purple-400">Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full px-3 py-2 bg-gray-800 border border-purple-500 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="mb-4">
                    <label for="author" class="block mb-2 text-purple-400">Author *</label>
                    <input type="text" id="author" name="author" value="{{ old('author') }}" required class="w-full px-3 py-2 bg-gray-800 border border-purple-500 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="mb-4">
                    <label for="isbn" class="block mb-2 text-purple-400">ISBN *</label>
                    <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}" required class="w-full px-3 py-2 bg-gray-800 border border-purple-500 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="mb-4">
                    <label for="year" class="block mb-2 text-purple-400">Year *</label>
                    <input type="number" id="year" name="year" value="{{ old('year') }}" required class="w-full px-3 py-2 bg-gray-800 border border-purple-500 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="mb-4">
                    <label for="genre" class="block mb-2 text-purple-400">Genre</label>
                    <input type="text" id="genre" name="genre" value="{{ old('genre') }}" class="w-full px-3 py-2 bg-gray-800 border border-purple-500 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="mb-4">
                    <label for="publisher" class="block mb-2 text-purple-400">Publisher</label>
                    <input type="text" id="publisher" name="publisher" value="{{ old('publisher') }}" class="w-full px-3 py-2 bg-gray-800 border border-purple-500 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="mb-4">
                    <label for="pages" class="block mb-2 text-purple-400">Pages</label>
                    <input type="number" id="pages" name="pages" value="{{ old('pages') }}" class="w-full px-3 py-2 bg-gray-800 border border-purple-500 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="mb-4">
                    <label for="language" class="block mb-2 text-purple-400">Language</label>
                    <input type="text" id="language" name="language" value="{{ old('language', 'English') }}" class="w-full px-3 py-2 bg-gray-800 border border-purple-500 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <div class="mb-4">
                <label for="description" class="block mb-2 text-purple-400">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 bg-gray-800 border border-purple-500 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="image" class="block mb-2 text-purple-400">Book Cover Image</label>
                <input type="file" id="image" name="image" accept="image/*" class="w-full px-3 py-2 bg-gray-800 border border-purple-500 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Add Book</button>
                <a href="{{ url('/') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>