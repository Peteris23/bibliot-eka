<?php

namespace App\DataStructures;

use App\DataStructures\Book;

/**
 * Library class for managing books using an associative array (hash table).
 * Books are keyed by ISBN for efficient O(1) search, insert, and delete operations.
 */
class Library
{
    /**
     * @var array<string, Book> Associative array of books keyed by ISBN.
     */
    private array $books = [];

    /**
     * Add a new book to the library.
     * Time complexity: O(1) average case.
     */
    public function addBook(Book $book): void
    {
        $this->books[$book->isbn] = $book;
    }

    /**
     * Search for a book by ISBN.
     * Time complexity: O(1) average case.
     */
    public function getBookByIsbn(string $isbn): ?Book
    {
        return $this->books[$isbn] ?? null;
    }

    /**
     * Search for books by title (case-insensitive partial match).
     * Time complexity: O(n) where n is the number of books.
     *
     * @return Book[]
     */
    public function searchBooksByTitle(string $title): array
    {
        $results = [];
        $title = strtolower($title);
        foreach ($this->books as $book) {
            if (str_contains(strtolower($book->title), $title)) {
                $results[] = $book;
            }
        }
        return $results;
    }

    /**
     * Search for books by author (case-insensitive partial match).
     * Time complexity: O(n) where n is the number of books.
     *
     * @return Book[]
     */
    public function searchBooksByAuthor(string $author): array
    {
        $results = [];
        $author = strtolower($author);
        foreach ($this->books as $book) {
            if (str_contains(strtolower($book->author), $author)) {
                $results[] = $book;
            }
        }
        return $results;
    }

    /**
     * Delete a book by ISBN.
     * Time complexity: O(1) average case.
     */
    public function deleteBook(string $isbn): bool
    {
        if (isset($this->books[$isbn])) {
            unset($this->books[$isbn]);
            return true;
        }
        return false;
    }

    /**
     * Get all books.
     * Time complexity: O(1) for returning the array reference.
     *
     * @return Book[]
     */
    public function getAllBooks(): array
    {
        return $this->books;
    }

    /**
     * Get the number of books in the library.
     */
    public function count(): int
    {
        return count($this->books);
    }

    /**
     * Load books from persistent storage (database).
     * This method populates the in-memory structure from the database.
     */
    public function loadFromDatabase(): void
    {
        // This would be implemented to load from Eloquent models
        // For now, it's a placeholder
    }

    /**
     * Save books to persistent storage (database).
     * This method persists the in-memory structure to the database.
     */
    public function saveToDatabase(): void
    {
        // This would be implemented to save to Eloquent models
        // For now, it's a placeholder
    }
}
