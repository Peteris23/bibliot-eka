<?php

namespace App\DataStructures;

/**
 * Book class representing a book entity with attributes.
 * This class is used for in-memory data structure management.
 */
class Book
{
    public int $id;
    public string $title;
    public string $author;
    public string $isbn;
    public int $year;
    public ?string $description;
    public bool $available;
    public ?string $genre;
    public ?string $image;

    public function __construct(?int $id, string $title, string $author, string $isbn, int $year, ?string $description = null, bool $available = true, ?string $genre = null, ?string $image = null)
    {
        $this->id = $id ?? 0;
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->year = $year;
        $this->description = $description;
        $this->available = $available;
        $this->genre = $genre;
        $this->image = $image;
    }

    /**
     * Get a string representation of the book.
     */
    public function __toString(): string
    {
        return "{$this->title} by {$this->author} (ISBN: {$this->isbn})";
    }
}
