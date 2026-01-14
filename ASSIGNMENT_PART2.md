# Bibliotēkas Grāmatu Pārvaldības Sistēma - 2. Daļa

## 1. Prasību Analīze un Datu Modelēšana

### 1.1 Prasību Dokuments

#### Funkcionālās prasības

**FR-1: Grāmatu pārvaldība**
- FR-1.1: Sistēmai jāspēj reģistrēt jaunu grāmatu (nosaukums, autors, ISBN, žanrs)
- FR-1.2: Sistēmai jāspēj meklēt grāmatas pēc nosaukuma, autora, žanra
- FR-1.3: Sistēmai jāspēj rediģēt grāmatas informāciju
- FR-1.4: Sistēmai jāspēj dzēst grāmatu no sistēmas
- FR-1.5: Sistēmai jāspēj attēlot visas pieejamās grāmatas

**FR-2: Lietotāju pārvaldība**
- FR-2.1: Sistēmai jāspēj reģistrēt jaunus lietotājus
- FR-2.2: Sistēmai jāspēj autentificēt lietotājus (ielogošanās)
- FR-2.3: Sistēmai jāatbalsta divas lietotāju lomas: administrators un lietotājs
- FR-2.4: Lietotājam jāspēj apskatīt savu profilu

**FR-3: Grāmatu aizņemšanās**
- FR-3.1: Lietotājam jāspēj aizņemties pieejamu grāmatu
- FR-3.2: Sistēmai jāreģistrē aizņemšanās datums un termiņš
- FR-3.3: Lietotājam jāspēj atgriezt grāmatu
- FR-3.4: Sistēmai jāspēj attēlot lietotāja aizņemto grāmatu vēsturi

**FR-4: Meklēšana un filtrēšana**
- FR-4.1: Sistēmai jābūt meklēšanas funkcionalitātei
- FR-4.2: Meklēšanai jāatbalsta daļēju sakritību (partial match)
- FR-4.3: Rezultātiem jāattēlo grāmatas attēls, nosaukums, autors

#### Nefunkcionālās prasības

**NFR-1: Veiktspēja**
- Meklēšanas rezultāti jāattēlo < 500ms
- Sistēmai jāspēj apstrādāt vismaz 5000 grāmatas

**NFR-2: Drošība**
- Lietotāju paroles jāglabā šifrētā veidā (hash)
- Sesiju pārvaldība ar autentifikāciju

**NFR-3: Lietojamība**
- Moderna, responsive dizaina saskarnes
- Intuitīva navigācija

**NFR-4: Uzturēšana**
- Kods strukturēts pēc MVC arhitektūras
- Komentēts kods kritiskajās vietās

#### Lietotāju lomas un tiesības

| Funkcionalitāte | Administrators | Lietotājs | Viesis |
|----------------|----------------|-----------|---------|
| Skatīt grāmatas | ✓ | ✓ | ✓ |
| Meklēt grāmatas | ✓ | ✓ | ✓ |
| Pievienot grāmatu | ✓ | ✗ | ✗ |
| Rediģēt grāmatu | ✓ | ✗ | ✗ |
| Dzēst grāmatu | ✓ | ✗ | ✗ |
| Aizņemties grāmatu | ✓ | ✓ | ✗ |
| Skatīt aizņemto vēsturi | ✓ (visi) | ✓ (savu) | ✗ |

---

### 1.2 Konceptuālais Datu Modelis (ER Diagramma)

```
┌─────────────────┐
│      USER       │
├─────────────────┤
│ PK: id          │
│    name         │
│    email        │
│    password     │
│    role         │
│    created_at   │
└────────┬────────┘
         │
         │ 1
         │
         │ has many
         │
         │ N
         ┆
┌────────▼────────┐         N        ┌─────────────────┐
│      LOAN       │◄─────────────────►│      BOOK       │
├─────────────────┤      borrows     ├─────────────────┤
│ PK: id          │                  │ PK: id          │
│ FK: user_id     │                  │    title        │
│ FK: book_id     │                  │    author       │
│    borrowed_at  │                  │    isbn         │
│    due_date     │                  │    genre        │
│    returned_at  │                  │    image        │
│    created_at   │                  │    created_at   │
└─────────────────┘                  └─────────────────┘

Relationships:
- USER (1) ──< (N) LOAN : Lietotājs var aizņemties vairākas grāmatas
- BOOK (1) ──< (N) LOAN : Grāmata var būt aizņemta vairākas reizes
- USER-LOAN-BOOK: M:N attiecība caur LOAN tabulu
```

**Saišu apraksts:**
- **One-to-Many (1:N):** Viens lietotājs var veikt vairākus aizņēmumus
- **One-to-Many (1:N):** Viena grāmata var būt aizņemta vairākas reizes (laika gaitā)
- **Many-to-Many (M:N):** Vairāki lietotāji var aizņemties vairākas grāmatas (realizēts caur LOAN tabulu)

---

### 1.3 Loģiskais Datu Modelis

#### Tabula: users

| Lauka nosaukums | Datu tips | Ierobežojumi | Apraksts |
|----------------|-----------|--------------|----------|
| id | INTEGER | PRIMARY KEY, AUTO_INCREMENT | Unikāls lietotāja ID |
| name | VARCHAR(255) | NOT NULL | Lietotāja vārds |
| email | VARCHAR(255) | NOT NULL, UNIQUE | E-pasta adrese |
| password | VARCHAR(255) | NOT NULL | Šifrēta parole (hash) |
| role | VARCHAR(50) | DEFAULT 'user' | Lietotāja loma |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Reģistrācijas datums |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Pēdējās izmaiņas |

**Indeksi:**
- PRIMARY KEY: id
- UNIQUE INDEX: email
- INDEX: role (filtrēšanai pēc lomām)

---

#### Tabula: books

| Lauka nosaukums | Datu tips | Ierobežojumi | Apraksts |
|----------------|-----------|--------------|----------|
| id | INTEGER | PRIMARY KEY, AUTO_INCREMENT | Unikāls grāmatas ID |
| title | VARCHAR(255) | NOT NULL | Grāmatas nosaukums |
| author | VARCHAR(255) | NOT NULL | Autora vārds |
| isbn | VARCHAR(20) | UNIQUE | ISBN numurs |
| genre | VARCHAR(100) | NULL | Žanrs |
| image | VARCHAR(255) | NULL | Attēla ceļš |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Pievienošanas datums |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Pēdējās izmaiņas |

**Indeksi:**
- PRIMARY KEY: id
- UNIQUE INDEX: isbn
- INDEX: title (meklēšanai)
- INDEX: author (meklēšanai)
- INDEX: genre (filtrēšanai)

---

#### Tabula: loans

| Lauka nosaukums | Datu tips | Ierobežojumi | Apraksts |
|----------------|-----------|--------------|----------|
| id | INTEGER | PRIMARY KEY, AUTO_INCREMENT | Unikāls aizņēmuma ID |
| user_id | INTEGER | FOREIGN KEY (users.id) ON DELETE CASCADE | Lietotāja atsauce |
| book_id | INTEGER | FOREIGN KEY (books.id) ON DELETE CASCADE | Grāmatas atsauce |
| borrowed_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Aizņemšanās datums |
| due_date | DATE | NOT NULL | Atgriešanas termiņš |
| returned_at | TIMESTAMP | NULL | Faktiskais atgriešanas datums |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Ieraksta izveides datums |
| updated_at | TIMESTAMP | ON UPDATE CURRENT_TIMESTAMP | Pēdējās izmaiņas |

**Indeksi:**
- PRIMARY KEY: id
- FOREIGN KEY: user_id → users(id)
- FOREIGN KEY: book_id → books(id)
- INDEX: (user_id, book_id) - kombinēts indeks
- INDEX: borrowed_at (vēstures vaicājumiem)
- INDEX: returned_at (aktīvo aizņēmumu filtrēšanai)

**Biznesa loģika:**
- Grāmata ir pieejama aizņemšanai, ja loans tabulā nav ieraksta ar book_id, kur returned_at IS NULL
- Aizņemšanās periods: 14 dienas (due_date = borrowed_at + 14 days)

---

## 2. Datu Struktūru Pārvaldība un Ieviešana

### 2.1 Datu Struktūras Izvēle

**Izvēlētā datu struktūra:** Eloquent ORM ar Relāciju Datubāzi (SQLite/MySQL)

**Pamatojums:**

1. **Hash Table (caur indeksiem):**
   - Meklēšana pēc ID: O(1)
   - Indeksi uz title, author: Ātra meklēšana O(log n)

2. **Saistītie saraksti (caur relācijām):**
   - Users ↔ Loans ↔ Books
   - Efektīva daudz-pret-daudziem attiecību pārvaldība

3. **Kolekcijas (Laravel Collections):**
   - Atgriež masīva līdzīgu struktūru ar iterācijas metodēm
   - Optimizēti vaicājumi ar lazy loading

**Veiktspējas analīze:**

| Operācija | Laika sarežģītība | Paskaidrojums |
|-----------|-------------------|---------------|
| Meklēt pēc ID | O(1) | Primary key index |
| Meklēt pēc nosaukuma | O(log n) | B-Tree index uz title |
| Pievienot grāmatu | O(1) | INSERT operācija |
| Dzēst grāmatu | O(1) | DELETE ar indeksu |
| Atrast lietotāja aizņēmumus | O(k) | k = aizņēmumu skaits |

---

### 2.2 Klašu Struktūra

#### Book.php (Eloquent Model)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Grāmatas modelis
 * 
 * Atmiņas izvietojums:
 * - Objekta instance glabājas heap atmiņā
 * - Atribūti glabājas kā objekta properties
 * - Eloquent izmanto lazy loading - dati tiek ielādēti tikai kad nepieciešams
 */
class Book extends Model
{
    // Tabulas nosaukums
    protected $table = 'books';
    
    // Masveida piešķiramie lauki (mass assignment)
    protected $fillable = [
        'title',
        'author', 
        'isbn',
        'genre',
        'image'
    ];
    
    // Lauka tipu casting
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Relācija: Grāmata var būt aizņemta vairākas reizes
     * One-to-Many attiecība
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
    
    /**
     * Pārbauda vai grāmata ir pieejama aizņemšanai
     * 
     * @return bool
     */
    public function isAvailable(): bool
    {
        return !$this->loans()
            ->whereNull('returned_at')
            ->exists();
    }
    
    /**
     * Iegūst aktīvo aizņēmumu (ja eksistē)
     */
    public function currentLoan()
    {
        return $this->loans()
            ->whereNull('returned_at')
            ->first();
    }
}
```

#### User.php (Eloquent Model)

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Lietotāja modelis
 * Paplašina Laravel Authentication funkcionalitāti
 */
class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Automātiska hash
    ];
    
    /**
     * Relācija: Lietotājs var veikt vairākus aizņēmumus
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
    
    /**
     * Iegūst lietotāja aktīvos aizņēmumus
     */
    public function activeLoans()
    {
        return $this->loans()
            ->whereNull('returned_at')
            ->with('book');
    }
    
    /**
     * Pārbauda vai lietotājs ir administrators
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
```

#### Loan.php (Eloquent Model)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Aizņēmuma modelis
 * Savieno User un Book (junction table)
 */
class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_date',
        'returned_at'
    ];
    
    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_date' => 'date',
        'returned_at' => 'datetime',
    ];
    
    /**
     * Relācija: Aizņēmums pieder lietotājam
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relācija: Aizņēmums ir par grāmatu
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    
    /**
     * Pārbauda vai aizņēmums ir nokavēts
     */
    public function isOverdue(): bool
    {
        return $this->returned_at === null 
            && $this->due_date->isPast();
    }
}
```

---

### 2.3 CRUD Funkcionalitātes Implementācija

Implementēts BookController.php ar visām CRUD operācijām un meklēšanas API.

**Galvenās funkcijas:**
- `index()` - Attēlo visas grāmatas
- `create()` - Forma jaunas grāmatas pievienošanai
- `store()` - Saglabā jaunu grāmatu
- `search()` - API endpoint meklēšanai
- `destroy()` - Dzēš grāmatu

**Meklēšanas optimizācija:**
```php
$books = Book::query()
    ->when($query, function($q) use ($query) {
        $q->where('title', 'LIKE', "%{$query}%")
          ->orWhere('author', 'LIKE', "%{$query}%")
          ->orWhere('genre', 'LIKE', "%{$query}%");
    })
    ->get();
```

---

## 3. Datu Glabāšanas Sistēmas Izvēle

### 3.1 Izvēlētā sistēma: SQLite (Relāciju Datubāze)

**Pamatojums:**

#### Kāpēc SQL datubāze?

1. **Strukturēti dati:**
   - Grāmatu, lietotāju un aizņēmumu dati ir strukturēti
   - Skaidras attiecības (relācijas) starp entītijām
   - FOREIGN KEY integritātes ierobežojumi

2. **ACID īpašības:**
   - **Atomicity:** Transakcija vai nu notiek pilnībā, vai nenotiek
   - **Consistency:** Dati vienmēr konsistentā stāvoklī
   - **Isolation:** Vairāki lietotāji var strādāt vienlaicīgi
   - **Durability:** Dati saglabājas pēc transakcijas

3. **Vaicājumu iespējas:**
   - SQL JOIN operācijas relāciju vaicājumiem
   - WHERE, ORDER BY, GROUP BY filtrēšanai
   - Indeksi ātrai meklēšanai

4. **Mērogojamība:**
   - SQLite: Līdz 1M ierakstiem (~140TB)
   - Viegli pārejama uz MySQL/PostgreSQL

#### Kāpēc tieši SQLite?

- **Vienkāršība:** Nav nepieciešams atsevišķs serveris
- **Lietderīgs maziem projektiem:** Līdz ~10000 grāmatas
- **File-based:** Viss datu bāzē vienā failā
- **Zero-configuration:** Automātiski darbojas Laravel vidē
- **Testējamība:** Viegli izveidot test datubāzes

### 3.2 Salīdzinājums ar citām opcijām

| Aspekts | SQLite (izvēlēts) | Teksta fails | NoSQL (MongoDB) | MySQL |
|---------|-------------------|--------------|-----------------|--------|
| Meklēšanas ātrums | ⭐⭐⭐⭐ (indexed) | ⭐ (O(n)) | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Relāciju atbalsts | ⭐⭐⭐⭐⭐ | ⭐ | ⭐⭐ | ⭐⭐⭐⭐⭐ |
| Iestatīšanas vienkāršība | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐ |
| ACID garantijas | ⭐⭐⭐⭐⭐ | ⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Mērogojamība | ⭐⭐⭐ | ⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Piemērots projektam | ✅ | ❌ | ⚠️ | ✅ |

**Kāpēc NE teksta fails:**
- Nav indeksēšanas - O(n) meklēšana
- Nav relāciju - grūti pārvaldīt saites
- Datu konsistences problēmas
- Fails jāpārraksta pilnībā pēc katras izmaiņas

**Kāpēc NE NoSQL:**
- Bibliotēkas dati ir augsti strukturēti
- Skaidras relācijas starp entītijām
- Nenepiešama NoSQL priekšrocības (flexible schema, horizontal scaling)

---

### 3.3 Datu Persistences Implementācija

#### Migrācijas (Database Schema)

Laravel migrācijas nodrošina version control datubāzes struktūrai:

```php
// 2026_01_10_170208_create_books_table.php
Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('author');
    $table->string('isbn')->unique()->nullable();
    $table->string('genre')->nullable();
    $table->string('image')->nullable();
    $table->timestamps();
});
```

#### Seeders (Testa dati)

```php
// DatabaseSeeder.php
Book::factory()->count(50)->create();
```

#### Backup un Recovery

1. **Automātiskā saglabāšana:**
   - Katrs Eloquent::save() izpilda SQL INSERT/UPDATE
   - Transakcijas nodrošina datu integritāti

2. **Manuālā dublēšana:**
```bash
# SQLite backup
cp database/database.sqlite database/backups/backup_$(date +%Y%m%d).sqlite

# MySQL backup
mysqldump -u root -p biblioteka > backup.sql
```

3. **Atjaunošana:**
```bash
# SQLite restore
cp database/backups/backup_20260113.sqlite database/database.sqlite

# MySQL restore
mysql -u root -p biblioteka < backup.sql
```

---

## Kopsavilkums

### Tehnoloģiju steks:
- **Backend:** Laravel 12 (PHP 8.3)
- **Database:** SQLite (development), MySQL ready
- **ORM:** Eloquent
- **Frontend:** Blade templates, Tailwind CSS, Vanilla JavaScript
- **Architecture:** MVC pattern

### Galvenās stiprās puses:
1. ✅ Skaidra MVC arhitektūra
2. ✅ Relāciju datubāze ar ACID garantijām
3. ✅ Indeksēta meklēšana (O(log n))
4. ✅ Type-safe Eloquent modeļi
5. ✅ Migrācijas version control
6. ✅ Viegli mērogojama uz production DB

### Projekta atbilstība prasībām:
- ✅ Prasību dokuments definēts
- ✅ ER diagramma izveidota
- ✅ Loģiskais modelis ar detalizētām shēmām
- ✅ Piemērota datu struktūra (Eloquent + SQL)
- ✅ Pilna CRUD funkcionalitāte
- ✅ SQL datubāze ar pamatojumu
- ✅ Persistance implementēta
