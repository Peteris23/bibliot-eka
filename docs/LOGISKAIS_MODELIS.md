# Loģiskais Datu Modelis (Tabulu Shēmas)

## 1. Ievads

Loģiskais datu modelis pārveido konceptuālo modeli (ER diagrammu) par konkrētām datubāzes tabulu shēmām ar precīziem datu tipiem, ierobežojumiem un indeksiem. Šis dokuments detalizēti apraksta katru tabulu, tās laukus un saites.

---

## 2. Tabulu Shēmu Apraksts

### 2.1 Tabula: `users`

**Mērķis:** Glabā sistēmas lietotāju informāciju ar autentifikācijas datiem.

| Lauka Nosaukums | Datu Tips | Garums | Ierobežojumi | Noklusējums | Apraksts |
|----------------|-----------|---------|-------------|------------|----------|
| `id` | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT | - | Unikāls lietotāja identifikators |
| `name` | VARCHAR | 255 | NOT NULL | - | Lietotāja pilnais vārds |
| `email` | VARCHAR | 255 | NOT NULL, UNIQUE | - | E-pasta adrese (izmanto pieteikšanai) |
| `email_verified_at` | TIMESTAMP | - | NULLABLE | NULL | E-pasta verifikācijas laiks |
| `password` | VARCHAR | 255 | NOT NULL | - | Hešēta parole (bcrypt) |
| `role` | VARCHAR | 50 | NOT NULL | 'user' | Lietotāja loma (admin/librarian/user) |
| `remember_token` | VARCHAR | 100 | NULLABLE | NULL | "Atcerēties mani" funkcijas tokens |
| `created_at` | TIMESTAMP | - | NOT NULL | CURRENT_TIMESTAMP | Reģistrācijas datums |
| `updated_at` | TIMESTAMP | - | NOT NULL | CURRENT_TIMESTAMP ON UPDATE | Pēdējās izmaiņas datums |

**Primārā Atslēga:** `id`

**Unikālie Indeksi:**
- `users_email_unique` uz `email`

**Parastie Indeksi:**
- Indekss uz `role` (meklēšanai pēc lomas)

**Validācijas Noteikumi:**
- `email`: Jābūt derīgam e-pasta formātam
- `password`: Minimums 8 simboli
- `role`: Atļautās vērtības: 'admin', 'librarian', 'user'

**SQL Schema:**
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'user',
    remember_token VARCHAR(100) NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 2.2 Tabula: `books`

**Mērķis:** Glabā bibliotēkas grāmatu kataloga informāciju.

| Lauka Nosaukums | Datu Tips | Garums | Ierobežojumi | Noklusējums | Apraksts |
|----------------|-----------|---------|-------------|------------|----------|
| `id` | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT | - | Unikāls grāmatas identifikators |
| `title` | VARCHAR | 255 | NOT NULL | - | Grāmatas nosaukums |
| `author` | VARCHAR | 255 | NOT NULL | - | Autora vārds |
| `isbn` | VARCHAR | 13 | NOT NULL, UNIQUE | - | Starptautiskais standarta grāmatas numurs |
| `year` | INTEGER | - | NOT NULL | - | Izdošanas gads |
| `description` | TEXT | - | NULLABLE | NULL | Grāmatas apraksts/anotācija |
| `genre` | VARCHAR | 100 | NULLABLE | NULL | Grāmatas žanrs |
| `image` | VARCHAR | 255 | NULLABLE | NULL | Vāka attēla ceļš |
| `publisher` | VARCHAR | 255 | NULLABLE | NULL | Izdevniecības nosaukums |
| `pages` | INTEGER | - | NULLABLE | NULL | Lappušu skaits |
| `language` | VARCHAR | 50 | NOT NULL | 'English' | Grāmatas valoda |
| `available` | BOOLEAN | - | NOT NULL | TRUE | Pieejamības statuss |
| `created_at` | TIMESTAMP | - | NOT NULL | CURRENT_TIMESTAMP | Pievienošanas datums |
| `updated_at` | TIMESTAMP | - | NOT NULL | CURRENT_TIMESTAMP ON UPDATE | Pēdējās izmaiņas datums |

**Primārā Atslēga:** `id`

**Unikālie Indeksi:**
- `books_isbn_unique` uz `isbn`

**Parastie Indeksi:**
- `idx_title` uz `title` (meklēšanai pēc nosaukuma)
- `idx_author` uz `author` (meklēšanai pēc autora)
- `idx_genre` uz `genre` (filtrēšanai pēc žanra)
- `idx_available` uz `available` (filtrēšanai pēc pieejamības)
- Composite indekss `idx_title_author` uz `(title, author)`

**Validācijas Noteikumi:**
- `isbn`: 10 vai 13 ciparu formāts, unikāls
- `year`: Starp 1000 un pašreizējo gadu
- `pages`: Pozitīvs skaitlis
- `title`, `author`: Nav tukši

**SQL Schema:**
```sql
CREATE TABLE books (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    isbn VARCHAR(13) NOT NULL UNIQUE,
    year INTEGER NOT NULL,
    description TEXT NULL DEFAULT NULL,
    genre VARCHAR(100) NULL DEFAULT NULL,
    image VARCHAR(255) NULL DEFAULT NULL,
    publisher VARCHAR(255) NULL DEFAULT NULL,
    pages INTEGER NULL DEFAULT NULL,
    language VARCHAR(50) NOT NULL DEFAULT 'English',
    available BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_title (title),
    INDEX idx_author (author),
    INDEX idx_genre (genre),
    INDEX idx_available (available),
    INDEX idx_title_author (title, author)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 2.3 Tabula: `loans`

**Mērķis:** Glabā grāmatu aizņemšanās ierakstus (association table starp users un books).

| Lauka Nosaukums | Datu Tips | Garums | Ierobežojumi | Noklusējums | Apraksts |
|----------------|-----------|---------|-------------|------------|----------|
| `id` | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT | - | Unikāls aizdevuma identifikators |
| `user_id` | BIGINT UNSIGNED | - | FOREIGN KEY, NOT NULL | - | Atsauce uz lietotāju (users.id) |
| `book_id` | BIGINT UNSIGNED | - | FOREIGN KEY, NOT NULL | - | Atsauce uz grāmatu (books.id) |
| `loan_date` | DATE | - | NOT NULL | - | Aizņemšanās datums |
| `due_date` | DATE | - | NOT NULL | - | Plānotais atgriešanas datums |
| `return_date` | DATE | - | NULLABLE | NULL | Faktiskais atgriešanas datums |
| `created_at` | TIMESTAMP | - | NOT NULL | CURRENT_TIMESTAMP | Ieraksta izveidošanas laiks |
| `updated_at` | TIMESTAMP | - | NOT NULL | CURRENT_TIMESTAMP ON UPDATE | Pēdējās izmaiņas laiks |

**Primārā Atslēga:** `id`

**Ārējās Atslēgas:**
- `loans_user_id_foreign`: `user_id` → `users(id)` ON DELETE CASCADE
- `loans_book_id_foreign`: `book_id` → `books(id)` ON DELETE CASCADE

**Parastie Indeksi:**
- `idx_user_id` uz `user_id` (lietotāja aizdevumu meklēšanai)
- `idx_book_id` uz `book_id` (grāmatas aizdevumu vēsturei)
- `idx_return_date` uz `return_date` (aktīvo aizdevumu meklēšanai)
- `idx_due_date` uz `due_date` (nokavēto aizdevumu meklēšanai)
- Composite indekss `idx_user_book_active` uz `(user_id, book_id, return_date)`

**Validācijas Noteikumi:**
- `due_date` >= `loan_date`
- `return_date` >= `loan_date` (ja nav NULL)
- Nedrīkst būt vairāki aktīvi (return_date IS NULL) aizdevumi vienai grāmatai
- Lietotājs nedrīkst aizņemties vairāk par 5 grāmatām vienlaicīgi

**Biznesa Loģika:**
- Aizdevums ir aktīvs, ja `return_date IS NULL`
- `due_date` parasti ir `loan_date + 14 dienas`
- Nokavēts aizdevums: `return_date IS NULL AND due_date < CURRENT_DATE`

**SQL Schema:**
```sql
CREATE TABLE loans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    book_id BIGINT UNSIGNED NOT NULL,
    loan_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT loans_user_id_foreign 
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT loans_book_id_foreign 
        FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_book_id (book_id),
    INDEX idx_return_date (return_date),
    INDEX idx_due_date (due_date),
    INDEX idx_user_book_active (user_id, book_id, return_date),
    CONSTRAINT chk_due_after_loan CHECK (due_date >= loan_date),
    CONSTRAINT chk_return_after_loan CHECK (return_date IS NULL OR return_date >= loan_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 2.4 Papildus Tabulas (Laravel Sistēmas Tabulas)

#### 2.4.1 Tabula: `password_reset_tokens`

**Mērķis:** Glabā paroles atjaunošanas tokenus.

| Lauka Nosaukums | Datu Tips | Garums | Ierobežojumi | Apraksts |
|----------------|-----------|---------|-------------|----------|
| `email` | VARCHAR | 255 | PRIMARY KEY | Lietotāja e-pasts |
| `token` | VARCHAR | 255 | NOT NULL | Atjaunošanas tokens (hešēts) |
| `created_at` | TIMESTAMP | - | NULLABLE | Tokena izveides laiks |

#### 2.4.2 Tabula: `sessions`

**Mērķis:** Glabā lietotāju sesiju informāciju.

| Lauka Nosaukums | Datu Tips | Garums | Ierobežojumi | Apraksts |
|----------------|-----------|---------|-------------|----------|
| `id` | VARCHAR | 255 | PRIMARY KEY | Sesijas ID |
| `user_id` | BIGINT UNSIGNED | - | NULLABLE, INDEX | Atsauce uz lietotāju |
| `ip_address` | VARCHAR | 45 | NULLABLE | IP adrese |
| `user_agent` | TEXT | - | NULLABLE | Pārlūkprogrammas info |
| `payload` | LONGTEXT | - | NOT NULL | Sesijas dati |
| `last_activity` | INTEGER | - | NOT NULL, INDEX | Pēdējās aktivitātes Unix timestamp |

---

## 3. Saišu Detalizēts Apraksts

### 3.1 Ārējās Atslēgas un Referential Integrity

#### Saite: `loans.user_id` → `users.id`

**Apraksts:** Katrs aizdevums pieder vienam lietotājam.

**Konfigurācija:**
```sql
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
```

**Darbības:**
- **ON DELETE CASCADE:** Izdzēšot lietotāju, tiek izdzēsti visi viņa aizdevumi
- **ON UPDATE CASCADE:** (noklusējums) Atjauninot users.id, atjauninās loans.user_id

**Pamatojums:**
- CASCADE izvēlēts, jo aizdevumi bez lietotāja nav jēgpilni
- Saglabā datu integritāti

---

#### Saite: `loans.book_id` → `books.id`

**Apraksts:** Katrs aizdevums attiecas uz vienu grāmatu.

**Konfigurācija:**
```sql
FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
```

**Darbības:**
- **ON DELETE CASCADE:** Izdzēšot grāmatu, tiek izdzēsti visi tās aizdevumi
- **ON UPDATE CASCADE:** (noklusējums) Atjauninot books.id, atjauninās loans.book_id

**Alternatīva Pieeja:**
```sql
FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE RESTRICT
```
- RESTRICT neļautu dzēst grāmatu, ja tai ir aizdevumu vēsture
- Noderīgs auditācijas nolūkos

---

## 4. Indeksu Stratēģija

### 4.1 Primārie Indeksi

Visām tabulām ir primārā atslēga (`id`), kas automātiski izveido clustered indeksu MySQL InnoDB.

### 4.2 Unikālie Indeksi

| Tabula | Indeksa Nosaukums | Kolonnas | Mērķis |
|--------|------------------|----------|---------|
| users | users_email_unique | email | Novērš dublētus e-pastus |
| books | books_isbn_unique | isbn | Novērš dublētus ISBN |

### 4.3 Ārējo Atslēgu Indeksi

Laravel automātiski izveido indeksus uz ārējām atslēgām:
- `loans.user_id`
- `loans.book_id`

### 4.4 Meklēšanas Indeksi

**Tabula `books`:**
```sql
INDEX idx_title (title)           -- Meklēšana pēc nosaukuma
INDEX idx_author (author)         -- Meklēšana pēc autora
INDEX idx_genre (genre)           -- Filtrēšana pēc žanra
INDEX idx_available (available)   -- Filtrēšana pēc pieejamības
INDEX idx_title_author (title, author)  -- Kombinēta meklēšana
```

**Tabula `loans`:**
```sql
INDEX idx_return_date (return_date)  -- Aktīvo aizdevumu meklēšana
INDEX idx_due_date (due_date)        -- Nokavēto aizdevumu meklēšana
INDEX idx_user_book_active (user_id, book_id, return_date)  -- Vairākkritēriju meklēšana
```

### 4.5 Indeksu Veiktspējas Analīze

| Vaicājuma Tips | Indekss | Kompleksitāte | Piezīmes |
|----------------|---------|---------------|----------|
| Grāmata pēc ISBN | books_isbn_unique | O(log n) | B-tree indekss |
| Grāmatas pēc nosaukuma | idx_title | O(log n + k) | k = atrasto ierakstu skaits |
| Lietotāja aktīvie aizdevumi | idx_user_id + return_date filter | O(log n + k) | Composite indekss vēl efektīvāks |
| Nokavētie aizdevumi | idx_due_date + return_date filter | O(log n + k) | Izmanto 2 indeksus |

---

## 5. Datu Tipu Izvēles Pamatojums

### 5.1 ID Lauki: BIGINT UNSIGNED AUTO_INCREMENT

**Pamatojums:**
- BIGINT (8 bytes): Atbalsta līdz 18,446,744,073,709,551,615 ierakstiem
- UNSIGNED: Tikai pozitīvi skaitļi, dubulto kapacitāti
- AUTO_INCREMENT: Automātiska unikāla vērtība

**Alternatīvas:**
- INT (4 bytes): Līdz 4 miljardiem ierakstu (pietiekami nelielai bibliotēkai)
- UUID: 36 simboli, nav sekvenciāls, lielāks izmērs

### 5.2 VARCHAR vs TEXT

**VARCHAR(255) - Lietots:**
- Fiksēta maksimālā garuma lauki
- Lauki, uz kuriem vajag indeksus
- Piemēri: email, title, author, isbn

**TEXT - Lietots:**
- Mainīga garuma saturs
- Liels teksts (līdz 65,535 baiti)
- Piemēri: description
- Nevar indeksēt pilnu TEXT kolonnu (tikai prefix)

### 5.3 DATE vs TIMESTAMP

**DATE:**
- Lietots: loan_date, due_date, return_date
- Pamatojums: Nepieciešams tikai datums, ne laiks
- Izmērs: 3 bytes
- Diapazons: 1000-01-01 līdz 9999-12-31

**TIMESTAMP:**
- Lietots: created_at, updated_at, email_verified_at
- Pamatojums: Nepieciešams precīzs laiks
- Izmērs: 4 bytes
- Diapazons: 1970-01-01 līdz 2038-01-19 (UTC)
- Automātiska atjaunināšana ar ON UPDATE CURRENT_TIMESTAMP

### 5.4 BOOLEAN (TINYINT(1))

**Lietots:** available
- MySQL BOOLEAN ir alias TINYINT(1)
- Vērtības: 0 (false), 1 (true)
- Izmērs: 1 byte
- Efektīvs indeksēšanai

---

## 6. Ierobežojumi (Constraints)

### 6.1 NOT NULL Ierobežojumi

**Obligātie Lauki:**
- Visi ID lauki
- Lietotāju: name, email, password
- Grāmatu: title, author, isbn, year
- Aizdevumu: user_id, book_id, loan_date, due_date

**Pamatojums:** Šie lauki ir kritiski biznesa loģikai.

### 6.2 CHECK Ierobežojumi

```sql
-- Aizdevumu tabulā
CONSTRAINT chk_due_after_loan 
    CHECK (due_date >= loan_date)

CONSTRAINT chk_return_after_loan 
    CHECK (return_date IS NULL OR return_date >= loan_date)
```

**Pamatojums:** Nodrošina datu loģisko integritāti.

### 6.3 Application-Level Constraints

Daži ierobežojumi tiek pārbaudīti aplikācijas līmenī (Laravel validācija):

```php
// Lietotāja aizdevumu limits
Rule::unique('loans')
    ->where('user_id', $userId)
    ->whereNull('return_date')
    ->count() < 5

// ISBN formāta validācija
Rule::regex('/^(\d{10}|\d{13})$/')

// E-pasta formāts
Rule::email()
```

---

## 7. Normalizācijas Analīze

### 7.1 Pirmā Normālforma (1NF)

✅ **Izpildīta:**
- Visi lauki satur tikai atomārus vērtības
- Nav atkārtotu grupu
- Katra kolonna satur tikai viena veida datus

**Piemērs:**
```
✗ Slikti: author = "J.K. Rowling, Stephen King"
✓ Labi:   author = "J.K. Rowling" (viens ieraksts)
          author = "Stephen King" (cits ieraksts)
```

### 7.2 Otrā Normālforma (2NF)

✅ **Izpildīta:**
- Izpilda 1NF
- Visi ne-atslēgu atribūti ir pilnībā atkarīgi no primārās atslēgas
- Nav daļēju atkarību

**Piemērs:**
```
loans tabula:
- Visi lauki (loan_date, due_date, return_date) ir atkarīgi no id
- Nav atkarīgi tikai no user_id vai book_id
```

### 7.3 Trešā Normālforma (3NF)

✅ **Izpildīta:**
- Izpilda 2NF
- Nav tranzitīvu atkarību
- Visi ne-atslēgu atribūti ir tieši atkarīgi no primārās atslēgas

**Potenciālais tranzitīvais atribūts:**
```
books.available ← šis varētu būt aprēķināts no loans
```

**Pamatojums denormalizācijai:**
- Veiktspējas optimizācija (meklēšanai pēc pieejamības)
- Nav nepieciešams JOIN ar loans tabulu
- Tradeoff: Jāatjaunina manuāli, kad mainās aizdevumu statuss

### 7.4 Boyce-Codd Normālforma (BCNF)

✅ **Izpildīta:**
- Visas determinantas ir kandidāt-atslēgas
- Šajā shēmā netiek pārkāpti BCNF noteikumi

---

## 8. Datubāzes Diagramma (Schema Visualization)

### 8.1 Attiecību Diagramma

```
┌─────────────────────┐
│       USERS         │
│ ─────────────────── │
│ PK: id              │
│     name            │
│     email (UNIQUE)  │
│     password        │
│     role            │
└──────────┬──────────┘
           │ 1
           │
           │ borrows
           │
           │ N
      ┌────▼─────────────┐
      │      LOANS       │
      │ ──────────────── │
      │ PK: id           │
      │ FK: user_id      │─┐
      │ FK: book_id      │ │
      │     loan_date    │ │
      │     due_date     │ │
      │     return_date  │ │
      └──────────────────┘ │
           │ N             │
           │               │
           │ references    │
           │               │
           │ 1             │
┌──────────▼────────────┐  │
│       BOOKS          │◄─┘
│ ──────────────────── │
│ PK: id               │
│     title            │
│     author           │
│     isbn (UNIQUE)    │
│     genre            │
│     year             │
│     available        │
└──────────────────────┘
```

### 8.2 Kardinalitāšu Kopsavilkums

| Saite | Kardinalitāte | Apraksts |
|-------|--------------|----------|
| USER → LOAN | 1 : N | Viens lietotājs, daudzi aizdevumi |
| BOOK → LOAN | 1 : N | Viena grāmata, daudzi aizdevumi |

---

## 9. Datu Integritātes Nodrošināšana

### 9.1 Entity Integrity

- Katrai tabulai ir primārā atslēga
- Primārā atslēga ir AUTO_INCREMENT (garantē unikālumu)
- Primārā atslēga ir NOT NULL

### 9.2 Referential Integrity

- Ārējās atslēgas ar CASCADE vai RESTRICT
- Indeksi uz ārējām atslēgām (veiktspējai)
- Cannot delete/update parent row if child rows exist (ja RESTRICT)

### 9.3 Domain Integrity

- CHECK constraints (due_date >= loan_date)
- NOT NULL constraints kritiskiem laukiem
- UNIQUE constraints (email, isbn)
- Application-level validation

### 9.4 User-Defined Integrity

- Biznesa noteikumi (max 5 aktīvie aizdevumi)
- Trigeri (piemēram, automātiska `books.available` atjaunināšana)
- Stored procedures transakciju loģikai

---

## 10. Veiktspējas Optimizācija

### 10.1 Indeksu Optimizācija

**Biežākie Vaicājumi:**

```sql
-- 1. Meklēt grāmatu pēc ISBN (O(log n))
SELECT * FROM books WHERE isbn = '9780132350884';
-- Izmanto: books_isbn_unique

-- 2. Meklēt grāmatas pēc nosaukuma (O(log n + k))
SELECT * FROM books WHERE title LIKE '%Harry Potter%';
-- Izmanto: idx_title

-- 3. Lietotāja aktīvie aizdevumi (O(log n + k))
SELECT * FROM loans 
WHERE user_id = 123 AND return_date IS NULL;
-- Izmanto: idx_user_book_active

-- 4. Nokavētie aizdevumi (O(log n + k))
SELECT * FROM loans 
WHERE return_date IS NULL AND due_date < CURRENT_DATE;
-- Izmanto: idx_due_date, idx_return_date
```

### 10.2 Query Optimization

**Composite Index izmantošana:**
```sql
-- Izveidots: INDEX idx_user_book_active (user_id, book_id, return_date)

-- Efektīvi vaicājumi:
SELECT * FROM loans WHERE user_id = 123;  -- Izmanto indeksu
SELECT * FROM loans WHERE user_id = 123 AND book_id = 456;  -- Izmanto indeksu
SELECT * FROM loans WHERE user_id = 123 AND return_date IS NULL;  -- Izmanto indeksu

-- NEefektīvs vaicājums:
SELECT * FROM loans WHERE book_id = 456;  -- Neizmanto indeksu (nav kreisākā kolonna)
```

### 10.3 Covering Indexes

Ideālā gadījumā, indekss satur visas nepieciešamās kolonnas:

```sql
-- Covering index piemērs
CREATE INDEX idx_user_loan_info ON loans(user_id, loan_date, due_date, return_date);

-- Šis vaicājums var tikt izpildīts tikai no indeksa (bez piekļuves tabulai)
SELECT loan_date, due_date FROM loans WHERE user_id = 123;
```

---

## 11. Migrāciju Secība

Laravel migrācijas jāizpilda noteiktā secībā, lai ievērotu ārējo atslēgu atkarības:

1. `0001_01_01_000000_create_users_table.php` - Izveido users tabulu
2. `0001_01_01_000001_create_cache_table.php` - Cache tabula (Laravel)
3. `0001_01_01_000002_create_jobs_table.php` - Jobs tabula (Laravel)
4. `2026_01_10_170208_create_books_table.php` - Izveido books tabulu
5. `2026_01_10_170225_create_loans_table.php` - Izveido loans tabulu (atkarīga no users un books)
6. `2026_01_10_172043_add_role_to_users_table.php` - Pievieno role kolonnu
7. `2026_01_10_173539_add_genre_and_image_to_books_table.php` - Pievieno papildus kolonnas
8. `2026_01_13_180706_add_more_fields_to_books_table.php` - Pievieno publisher, pages, language

---

## 12. Kopsavilkums

Loģiskais datu modelis sastāv no 3 galvenajām tabulām:

| Tabula | Primārā Atslēga | Ārējās Atslēgas | Unikālie Lauki | Indeksi |
|--------|----------------|----------------|---------------|---------|
| users | id | - | email | 2 |
| books | id | - | isbn | 6 |
| loans | id | user_id, book_id | - | 6 |

**Galvenās Priekšrocības:**
- ✅ Pilnībā normalizēts (3NF/BCNF)
- ✅ Efektīvi indeksi meklēšanai
- ✅ Referential integrity ar ārējām atslēgām
- ✅ Optimizēts biežākajiem vaicājumiem
- ✅ Mērogojams līdz simtiem tūkstošu ierakstu

**Dokumenta versija:** 1.0  
**Pēdējā atjaunināšana:** 2026-01-14
