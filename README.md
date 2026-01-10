# Library Management System

A data structure and storage system for a small library built with Laravel PHP and MySQL.

## Requirements Analysis

### Functional Requirements
- **Book Registration**: Add new books to the library with details like title, author, ISBN, year, and description.
- **User Registration**: Register library users (leveraging Laravel's built-in User model).
- **Book Borrowing**: Allow users to borrow books with loan tracking including loan date, due date, and return date.
- **Book Search**: Search books by title or author.
- **Book Management**: Update book information and remove books from the system.

### Non-Functional Requirements
- Efficient data structures for fast book operations.
- Persistent storage to maintain data between sessions.
- RESTful API for system interaction.

## Data Modeling

### Conceptual Data Model (ER Diagram)

```
+--------+       +--------+
|  Book  |       |  User  |
+--------+       +--------+
| id     |       | id     |
| title  |       | name   |
| author |       | email  |
| isbn   |       | ...    |
| year   |       +--------+
| desc   |
| avail  |
+--------+
    |
    | 1..*
    |
+--------+       +--------+
|  Loan  |       |  User  |
+--------+       +--------+
| id     |       |        |
| book_id|       |        |
| user_id|       |        |
| loan_dt|       |        |
| due_dt |       |        |
| ret_dt |       |        |
+--------+
```

**Entities and Relationships:**
- **Book**: Represents library books with unique ISBN.
- **User**: Represents library users (extends Laravel's User model).
- **Loan**: Represents book borrowing transactions.
- **Relationships**:
  - Book has many Loans (one-to-many).
  - User has many Loans (one-to-many).
  - Loan belongs to Book and User (many-to-one).

### Logical Data Model (Table Schemas)

#### Books Table
| Field       | Type         | Constraints          | Description              |
|-------------|--------------|----------------------|--------------------------|
| id          | BIGINT       | PRIMARY KEY, AUTO_INCREMENT | Unique identifier       |
| title       | VARCHAR(255) | NOT NULL             | Book title               |
| author      | VARCHAR(255) | NOT NULL             | Book author              |
| isbn        | VARCHAR(13)  | NOT NULL, UNIQUE     | ISBN-13 identifier       |
| year        | INT          | NOT NULL             | Publication year         |
| description | TEXT         | NULL                 | Book description         |
| available   | BOOLEAN      | DEFAULT TRUE         | Availability status      |
| created_at  | TIMESTAMP    | NULL                 | Creation timestamp       |
| updated_at  | TIMESTAMP    | NULL                 | Update timestamp         |

#### Users Table (Laravel Default)
| Field       | Type         | Constraints          | Description              |
|-------------|--------------|----------------------|--------------------------|
| id          | BIGINT       | PRIMARY KEY, AUTO_INCREMENT | Unique identifier       |
| name        | VARCHAR(255) | NOT NULL             | User name                |
| email       | VARCHAR(255) | NOT NULL, UNIQUE     | User email               |
| password    | VARCHAR(255) | NOT NULL             | Hashed password          |
| created_at  | TIMESTAMP    | NULL                 | Creation timestamp       |
| updated_at  | TIMESTAMP    | NULL                 | Update timestamp         |

#### Loans Table
| Field       | Type         | Constraints          | Description              |
|-------------|--------------|----------------------|--------------------------|
| id          | BIGINT       | PRIMARY KEY, AUTO_INCREMENT | Unique identifier       |
| book_id     | BIGINT       | FOREIGN KEY (books.id), CASCADE | Reference to book       |
| user_id     | BIGINT       | FOREIGN KEY (users.id), CASCADE | Reference to user       |
| loan_date   | DATE         | NOT NULL             | Date book was loaned     |
| due_date    | DATE         | NOT NULL             | Date book is due         |
| return_date | DATE         | NULL                 | Date book was returned   |
| created_at  | TIMESTAMP    | NULL                 | Creation timestamp       |
| updated_at  | TIMESTAMP    | NULL                 | Update timestamp         |

## Data Structures

### Book Class
The `Book` class represents a book entity with the following attributes:
- `title`: String - Book title
- `author`: String - Book author
- `isbn`: String - ISBN identifier
- `year`: Integer - Publication year
- `description`: String (nullable) - Book description
- `available`: Boolean - Availability status

**Memory Layout**: Each Book object is stored as a PHP object in memory, with properties allocated in the object's internal structure. The object itself is a reference to a structure containing the property table and values.

### Library Class
The `Library` class manages books using an associative array (hash table) for efficient operations.

**Data Structure Choice**: Associative array (PHP array with string keys).
- **Rationale**: Provides O(1) average time complexity for search, insert, and delete operations when using ISBN as the key. This is ideal for a library system where books are frequently looked up by ISBN.
- **Performance**:
  - **Search**: O(1) by ISBN
  - **Insert**: O(1) average case
  - **Delete**: O(1) average case
  - **Search by title/author**: O(n) as it requires iteration

**Memory Layout**: The associative array stores references to Book objects. Each array element contains a key-value pair where the key is the ISBN string and the value is a reference to the Book object.

## Storage System

### Chosen System: MySQL Database via Laravel Eloquent ORM

**Why MySQL?**
- **Relational Nature**: Perfect for the relationships between books, users, and loans.
- **ACID Compliance**: Ensures data consistency and integrity for transactions like borrowing books.
- **Scalability**: Suitable for a small library, with potential to scale.
- **Laravel Integration**: Seamless integration with Eloquent ORM for easy data manipulation.

**Comparison with Alternatives**:
- **Text Files**: Simple but lacks querying capabilities, data consistency, and concurrent access.
- **NoSQL (e.g., MongoDB)**: Flexible schema but overkill for relational data; less efficient for complex queries.
- **SQLite**: Good for small applications but MySQL provides better concurrent access and features.

**Persistence Implementation**:
- Data is loaded from MySQL into in-memory structures on application start.
- Changes are immediately persisted to the database.
- The system maintains state between sessions through the database.

## API Endpoints

### Books
- `GET /api/books` - List all books
- `POST /api/books` - Create a new book
- `GET /api/books/{isbn}` - Get a specific book
- `PUT /api/books/{isbn}` - Update a book
- `DELETE /api/books/{isbn}` - Delete a book
- `GET /api/books/search?q={query}&type={title|author}` - Search books

## Installation

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure database settings
4. Run `php artisan key:generate`
5. Run `php artisan migrate`
6. Start the server: `php artisan serve`

## Usage

Use tools like Postman or curl to interact with the API endpoints. The system maintains both in-memory data structures for fast operations and persistent database storage.
