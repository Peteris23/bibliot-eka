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
    public ?string $publisher;
    public ?int $pages;
    public string $language;

    public function __construct(?int $id, string $title, string $author, string $isbn, int $year, ?string $description = null, bool $available = true, ?string $genre = null, ?string $image = null, ?string $publisher = null, ?int $pages = null, string $language = 'English')
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
        $this->publisher = $publisher;
        $this->pages = $pages;
        $this->language = $language;
    }

    /**
     * Get a string representation of the book.
     */
    public function __toString(): string
    {
        return "{$this->title} by {$this->author} (ISBN: {$this->isbn})";
    }
}
