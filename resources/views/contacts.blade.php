<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contacts - Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-md rounded-lg p-4 mb-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Library Management System</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('library') }}" class="text-blue-600 hover:text-blue-800">Home</a>
                    <a href="{{ route('books.create') }}" class="text-blue-600 hover:text-blue-800">Create Books</a>
                    <a href="#search" class="text-blue-600 hover:text-blue-800">Search</a>
                    <a href="{{ route('contacts') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Contacts</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Contacts Content -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold mb-4">Contact Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium mb-2">Library Information</h3>
                    <p class="text-gray-600 mb-2">Address: 123 Library Street, Book City, BC 12345</p>
                    <p class="text-gray-600 mb-2">Phone: (123) 456-7890</p>
                    <p class="text-gray-600 mb-2">Email: info@library.com</p>
                    <p class="text-gray-600">Hours: Mon-Fri 9AM-6PM, Sat 10AM-4PM</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium mb-2">Contact Form</h3>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>