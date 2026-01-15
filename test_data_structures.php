<?php

require_once 'vendor/autoload.php';

use App\DataStructures\Book;
use App\DataStructures\Library;

// Test Book class
echo "Testing Book class:\n";
$book = new Book(null, "Test Book", "Test Author", "1234567890123", 2023, "A test description");
echo $book . "\n";
echo "ISBN: " . $book->isbn . "\n";
echo "Available: " . ($book->available ? 'Yes' : 'No') . "\n\n";

// Test Library class
echo "Testing Library class:\n";
$library = new Library();

// Add books
$book1 = new Book(null, "Book One", "Author One", "1111111111111", 2020);
$book2 = new Book(null, "Book Two", "Author Two", "2222222222222", 2021);
$book3 = new Book(null, "Another Book", "Author One", "3333333333333", 2022);

$library->addBook($book1);
$library->addBook($book2);
$library->addBook($book3);

echo "Total books: " . $library->count() . "\n";

// Test search by ISBN
$foundBook = $library->getBookByIsbn("1111111111111");
echo "Found book by ISBN: " . ($foundBook ? $foundBook->title : "Not found") . "\n";

// Test search by title
$titleResults = $library->searchBooksByTitle("Book");
echo "Books with 'Book' in title: " . count($titleResults) . "\n";

// Test search by author
$authorResults = $library->searchBooksByAuthor("Author One");
echo "Books by 'Author One': " . count($authorResults) . "\n";

// Test delete
$deleted = $library->deleteBook("2222222222222");
echo "Deleted book with ISBN 2222222222222: " . ($deleted ? "Yes" : "No") . "\n";
echo "Total books after delete: " . $library->count() . "\n";

echo "\nData structures test completed successfully!\n";
