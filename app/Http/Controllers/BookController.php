<?php

namespace App\Http\Controllers;

use App\DataStructures\Book as DataBook;
use App\DataStructures\Library;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    private Library $library;

    public function __construct()
    {
        $this->library = new Library();
        // Load books from database into memory structure
        $this->loadBooksFromDatabase();
    }

    /**
     * Load books from database into the in-memory library structure.
     */
    private function loadBooksFromDatabase(): void
    {
        $books = Book::all();
        foreach ($books as $book) {
            $dataBook = new DataBook(
                $book->id,
                $book->title,
                $book->author,
                $book->isbn,
                $book->year,
                $book->description,
                $book->available,
                $book->genre,
                $book->image,
                $book->publisher,
                $book->pages,
                $book->language
            );
            $this->library->addBook($dataBook);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $books = array_values($this->library->getAllBooks());
        return response()->json($books);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:13|unique:books,isbn',
            'year' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'description' => 'nullable|string',
            'genre' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
        }

        // Persist to database
        $data = $request->only(['title', 'author', 'isbn', 'year', 'description', 'genre', 'publisher', 'pages', 'language']);
        $data = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });
        $data['image'] = $imagePath;
        $data['available'] = true;
        $dbBook = Book::create($data);

        // Create in-memory book with the database ID
        $book = new DataBook(
            $dbBook->id,
            $request->title,
            $request->author,
            $request->isbn,
            $request->year,
            $request->description ?: null,
            true, // available
            $request->genre ?: null,
            $imagePath,
            $request->publisher ?: null,
            $request->pages ? (int)$request->pages : null,
            $request->language ?: 'English'
        );

        // Add to in-memory structure
        $this->library->addBook($book);

        return redirect()->back()->with('success', 'Book added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $isbn): JsonResponse
    {
        $book = $this->library->getBookByIsbn($isbn);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json($book);
    }

    /**
     * Search books by title or author.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        if ($query) {
            $books = $this->library->searchBooks($query);
        } else {
            $books = array_values($this->library->getAllBooks());
        }

        return response()->json($books);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $isbn): JsonResponse
    {
        $book = $this->library->getBookByIsbn($isbn);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'year' => 'sometimes|required|integer|min:1000|max:' . (date('Y') + 1),
            'description' => 'nullable|string',
            'available' => 'sometimes|boolean',
            'genre' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imagePath = $book->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
        }

        // Update in-memory book
        if ($request->has('title')) $book->title = $request->title;
        if ($request->has('author')) $book->author = $request->author;
        if ($request->has('year')) $book->year = $request->year;
        if ($request->has('description')) $book->description = $request->description;
        if ($request->has('available')) $book->available = $request->available;
        if ($request->has('genre')) $book->genre = $request->genre;
        $book->image = $imagePath;

        // Update in database
        $dbBook = Book::where('isbn', $isbn)->first();
        if ($dbBook) {
            $dbBook->update($request->only(['title', 'author', 'year', 'description', 'available', 'genre']) + ['image' => $imagePath]);
        }

        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $isbn): JsonResponse
    {
        $deleted = $this->library->deleteBook($isbn);
        if (!$deleted) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        // Delete from database
        Book::where('isbn', $isbn)->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }
}
