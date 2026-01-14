# Datu Glabāšanas Sistēmas Izvēle un Implementācija

## 1. Ievads

Šis dokuments detalizēti apraksta datu glabāšanas sistēmas izvēli bibliotēkas pārvaldības sistēmai, salīdzina dažādas alternatīvas un pamatojot galīgo lēmumu. Dokumentā ietverti arī implementācijas detaļi, datu persistences mehānismi un backup stratēģijas.

---

## 2. Datu Glabāšanas Alternatīvu Analīze

### 2.1 Alternatīva 1: Teksta Faili (CSV/JSON)

**Apraksts:** Vienkārša failu bāzēta glabāšana ar strukturētiem tekstas formātiem.

#### 2.1.1 CSV (Comma-Separated Values)

**Piemērs:**
```csv
id,title,author,isbn,year,available
1,"The Great Gatsby","F. Scott Fitzgerald","9780743273565",1925,true
2,"1984","George Orwell","9780451524935",1949,false
```

**Priekšrocības:**
- ✅ Vienkārša implementācija
- ✅ Nav nepieciešams ārējs serveris
- ✅ Viegli rediģējams ar Excel/teksta redaktoru
- ✅ Mazs file size
- ✅ Portable (pārnēsājams)

**Trūkumi:**
- ❌ Nav transakciju atbalsta (data corruption risks)
- ❌ Lēna meklēšana (jālasa viss fails): O(n)
- ❌ Grūti pārvaldīt saites (relāc relationships)
- ❌ Nav concurrency control (vairāki lietotāji)
- ❌ Sarežģīta datu integritātes nodrošināšana
- ❌ Problēmas ar speciāliem simboliem (quote escaping)
- ❌ File locking problēmas

**Veiktspēja:**
- Read: O(n) - jālasa viss fails
- Write: O(n) - jāpārraksta viss fails
- Update: O(n) - jālasa, jāmaina, jāraksta
- Delete: O(n)

**Piemērots:** Ļoti maziem projektiem (< 100 ieraksti), prototipēšanai.

---

#### 2.1.2 JSON (JavaScript Object Notation)

**Piemērs:**
```json
{
  "books": [
    {
      "id": 1,
      "title": "The Great Gatsby",
      "author": "F. Scott Fitzgerald",
      "isbn": "9780743273565",
      "year": 1925,
      "available": true
    }
  ]
}
```

**Priekšrocības:**
- ✅ Labāka datu struktūra (nested objects)
- ✅ Viegli parsējams
- ✅ Atbalsta dažādus datu tipus
- ✅ Human-readable

**Trūkumi:**
- ❌ Visi CSV trūkumi
- ❌ Lielāks file size
- ❌ Lēnāka parsēšana nekā binārajiem formātiem

**Piemērots:** Konfigurācijas faili, nelieli datu kopi.

---

#### 2.1.3 XML

**Priekšrocības:**
- ✅ Standartizēts formāts
- ✅ Atbalsta validāciju (XSD)

**Trūkumi:**
- ❌ Ļoti verbose (liels file size)
- ❌ Lēna parsēšana
- ❌ Sarežģītāks par JSON

**Piemērots:** Enterprise sistēmas ar stingru validāciju.

---

### 2.2 Alternatīva 2: SQLite (Embedded SQL Database)

**Apraksts:** Lokāla, failu-bāzēta SQL datubāze bez atsevišķa servera procesa.

**Priekšrocības:**
- ✅ SQL vaicājumi (strukturēta valoda)
- ✅ Transakciju atbalsts (ACID)
- ✅ Indeksi meklēšanas optimizācijai
- ✅ Foreign key constraints
- ✅ Nav nepieciešams atsevišķs serveris
- ✅ Cross-platform
- ✅ Viens fails = visa datubāze

**Trūkumi:**
- ❌ Ierobežots concurrency (vienu write vienlaicīgi)
- ❌ Nav piemērots lielajām datubāzēm (> 1GB)
- ❌ Lēnāks par MySQL/PostgreSQL
- ❌ Nav network access (tikai lokāla piekļuve)
- ❌ Ierobežoti datu tipi

**Veiktspēja:**
- Read: O(log n) ar indeksiem
- Write: O(log n)
- Transakcijas: Jā (ACID)

**Piemērots:** Lokālas aplikācijas, mobile apps, prototipi.

---

### 2.3 Alternatīva 3: MySQL (Relational Database Management System)

**Apraksts:** Pilnvērtīgs RDBMS ar klientu-servera arhitektūru.

**Priekšrocības:**
- ✅ Pilns ACID atbalsts
- ✅ Efektīvi B-Tree indeksi
- ✅ Vairāku lietotāju concurrency
- ✅ Transakciju līmeņi (isolation levels)
- ✅ Replication un high availability
- ✅ Backup tools (mysqldump, binary logs)
- ✅ Mērogojams (horizontal/vertical scaling)
- ✅ Foreign key constraints
- ✅ Stored procedures, triggers, views
- ✅ Full-text search

**Trūkumi:**
- ❌ Nepieciešams atsevišķs serveris
- ❌ Sarežģītāka uzstādīšana un konfigurācija
- ❌ Prasa vairāk resursus (RAM, CPU)

**Veiktspēja:**
- Read: O(log n) ar B-Tree indeksiem
- Write: O(log n)
- Transakcijas: Pilns ACID
- Concurrency: Lieliska (MVCC)

**Piemērots:** Produkcionālas web aplikācijas, vairāki lietotāji, kritiska datu integritāte.

---

### 2.4 Alternatīva 4: PostgreSQL

**Apraksts:** Advancēts open-source RDBMS.

**Priekšrocības:**
- ✅ Visi MySQL priekšrocības
- ✅ Labāks JSON atbalsts
- ✅ Advanced datu tipi (arrays, hstore, JSONB)
- ✅ Labāka standards compliance

**Trūkumi:**
- ❌ Sarežģītāks nekā MySQL
- ❌ Lēnāks simple queries (optimizēts complex queries)

**Piemērots:** Complex data models, advanced queries.

---

### 2.5 Alternatīva 5: NoSQL Datubāzes

#### 2.5.1 MongoDB (Document Store)

**Apraksts:** Schema-less document database.

**Priekšrocības:**
- ✅ Elastīga schema
- ✅ Ātrs horizontāls scaling
- ✅ Labi darbojas ar nested documents
- ✅ JSON-like documents

**Trūkumi:**
- ❌ Nav built-in JOIN (jāveic aplikācijas līmenī)
- ❌ Zemāka datu integritāte (nav foreign keys)
- ❌ Lielāks disk space izmantošana

**Piemērots:** Document-heavy aplikācijas, rapid prototyping, big data.

---

#### 2.5.2 Redis (Key-Value Store)

**Apraksts:** In-memory data structure store.

**Priekšrocības:**
- ✅ Ātrākais (viss atmiņā)
- ✅ Pub/sub, caching
- ✅ Advanced data structures (lists, sets, hashes)

**Trūkumi:**
- ❌ Nav primary database (tikai cache)
- ❌ Ierobežota datu persistences
- ❌ Ierobežota ar RAM

**Piemērots:** Caching, sessions, real-time analytics.

---

### 2.6 Alternatīvu Salīdzinājums

| Kritēriji | CSV/JSON | SQLite | MySQL | PostgreSQL | MongoDB |
|-----------|----------|--------|-------|-----------|---------|
| **ACID** | ❌ | ✅ | ✅ | ✅ | ⚠️ Partial |
| **Transakcijas** | ❌ | ✅ | ✅ | ✅ | ✅ |
| **Meklēšanas Ātrums** | O(n) | O(log n) | O(log n) | O(log n) | O(log n) |
| **Concurrency** | ❌ Slikts | ⚠️ Ierobežots | ✅ Lielisks | ✅ Lielisks | ✅ Labs |
| **Mērogojamība** | ❌ | ⚠️ Ierobežota | ✅ Laba | ✅ Laba | ✅ Lieliska |
| **Datu Integritāte** | ❌ | ✅ | ✅ | ✅ | ⚠️ Vidēja |
| **Setup Sarežģītība** | ✅ Vienkārša | ✅ Vienkārša | ⚠️ Vidēja | ⚠️ Vidēja | ⚠️ Vidēja |
| **Piemērots Projektam** | ❌ | ⚠️ | ✅ Ideāls | ✅ Labs | ⚠️ Overkill |

---

## 3. Galīgā Izvēle: MySQL

### 3.1 Lēmuma Pamatojums

**Izvēlēta Glabāšanas Sistēma:** MySQL 8.0+ (Relational Database)

#### 3.1.1 Galvenie Iemesli

1. **ACID Īpašības (Atomiškums, Konsekvence, Izolācija, Ilgstošība):**
   - Kritiskas bibliotēkas sistēmai (nevar zaudēt aizdevumu datus)
   - Garantē datu integritāti
   - Transakciju atbalsts sarežģītām operācijām

2. **Relational Model Atbilstība:**
   - Bibliotēkas domēns ir inherently relational:
     - Users have many Loans
     - Books have many Loans
     - Loans belong to User and Book
   - Foreign keys nodrošina referential integrity
   - SQL JOIN operācijas efektīvi apstrādā saites

3. **Veiktspēja:**
   - B-Tree indeksi: O(log n) meklēšana
   - Query optimizer
   - Covering indexes ātrākai piekļuvei
   - Full-text search support

4. **Mērogojamība:**
   - Atbalsta 100,000+ grāmatas
   - Vairāki vienlaicīgi lietotāji
   - Master-slave replication
   - Horizontal scaling iespējas

5. **Concurrency Control:**
   - MVCC (Multi-Version Concurrency Control)
   - Row-level locking
   - Vairāki lietotāji var lasīt un rakstīt vienlaicīgi

6. **Ecosystem un Atbalsts:**
   - Plaši izmantots (industrijas standarts)
   - Laba dokumentācija
   - Laravel Eloquent ORM pilns atbalsts
   - Daudz adminstrācijas tools (phpMyAdmin, MySQL Workbench)

7. **Backup un Recovery:**
   - mysqldump utility
   - Binary logs replication
   - Point-in-time recovery
   - Automated backup solutions

8. **Izmaksas:**
   - Open-source (GPL license)
   - Nav licensing fees
   - Plaši pieejams shared hosting

### 3.2 Salīdzinājums ar Alternatīvām

**Kāpēc NE CSV/JSON?**
- ❌ Nav transakciju atbalsta (data loss risks)
- ❌ Lēna meklēšana O(n)
- ❌ Nevar pārvaldīt saites efektīvi
- ❌ Nav concurrency control

**Kāpēc NE SQLite?**
- ⚠️ Ierobežots write concurrency (viens writer vienlaicīgi)
- ⚠️ Web aplikācijām nav ideāls (vairāki lietotāji)
- ⚠️ Mazāka mērogojamība

**Kāpēc NE PostgreSQL?**
- ✅ Būtu lielisks izvēle, bet:
- MySQL ir vairāk plaši atbalstīts shared hosting
- Vienkāršāks setup process
- Pietiekošs projektam (PostgreSQL advanced features nav nepieciešamas)

**Kāpēc NE MongoDB?**
- ❌ Document model nav optimāls relational datiem
- ❌ Nav built-in foreign keys
- ❌ Sarežģītāks JOIN operācijas
- ❌ Overkill mazai bibliotēkai

---

## 4. MySQL Implementācijas Detaļas

### 4.1 Datubāzes Konfigurācija

**Database Engine:** InnoDB

**Iemesli:**
- ✅ ACID transakcijas
- ✅ Foreign key constraints
- ✅ Row-level locking
- ✅ Crash recovery
- ✅ Noklusējuma engine MySQL 8.0+

**Alternatīva - MyISAM:**
- ❌ Nav transakciju
- ❌ Nav foreign keys
- ❌ Table-level locking
- ✅ Ātrāks read-only workloads (nav relevanti)

### 4.2 Laravel Database Configuration

**Konfigurācijas Fails:** `config/database.php`

```php
'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'biblioteka'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => 'InnoDB',
    ],
],
```

**Svarīgi Parametri:**
- `charset`: utf8mb4 (atbalsta visus Unicode simbolus, emoji)
- `collation`: utf8mb4_unicode_ci (case-insensitive salīdzinājumi)
- `strict`: true (strikti SQL režīms, labāka datu validācija)
- `engine`: InnoDB (transakcijas, foreign keys)

### 4.3 Environment Variables (`.env`)

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblioteka
DB_USERNAME=root
DB_PASSWORD=your_secure_password
```

---

## 5. Datu Persistences Implementācija

### 5.1 Eloquent ORM (Object-Relational Mapping)

Laravel izmanto Eloquent ORM, kas nodrošina objektu orientētu pieeju datubāzes operācijām.

#### 5.1.1 Model: Book

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title', 'author', 'isbn', 'year', 'description',
        'available', 'genre', 'image', 'publisher', 'pages', 'language'
    ];

    // Relationship: Book has many Loans
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
```

**Operācijas:**

```php
// CREATE - Pievienot jaunu grāmatu
$book = Book::create([
    'title' => 'The Great Gatsby',
    'author' => 'F. Scott Fitzgerald',
    'isbn' => '9780743273565',
    'year' => 1925
]);

// READ - Meklēt grāmatu pēc ISBN
$book = Book::where('isbn', '9780743273565')->first();

// UPDATE - Atjaunot pieejamību
$book->update(['available' => false]);

// DELETE - Dzēst grāmatu
$book->delete();
```

#### 5.1.2 Model: User

```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationship: User has many Loans
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
```

#### 5.1.3 Model: Loan

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'book_id', 'user_id', 'loan_date', 'due_date', 'return_date'
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    // Relationships
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### 5.2 Datu Saglabāšana un Ielāde

#### 5.2.1 Automātiska Persistences

Laravel Eloquent automātiski saglabā izmaiņas:

```php
// Automātiska INSERT
$book = new Book();
$book->title = "1984";
$book->author = "George Orwell";
$book->isbn = "9780451524935";
$book->year = 1949;
$book->save();  // SQL: INSERT INTO books ...

// Automātiska UPDATE
$book->available = false;
$book->save();  // SQL: UPDATE books SET available=0 WHERE id=?

// Mass Assignment
Book::create([
    'title' => 'Animal Farm',
    'author' => 'George Orwell',
    // ...
]);
```

#### 5.2.2 Transakciju Atbalsts

```php
use Illuminate\Support\Facades\DB;

// Aizdevuma reģistrācija ar transakciju
DB::transaction(function () use ($bookId, $userId) {
    // 1. Izveido aizdevumu
    $loan = Loan::create([
        'book_id' => $bookId,
        'user_id' => $userId,
        'loan_date' => now(),
        'due_date' => now()->addDays(14),
    ]);

    // 2. Atjaunina grāmatas pieejamību
    $book = Book::findOrFail($bookId);
    $book->update(['available' => false]);

    // Ja kāda operācija failojas, viss rollback automātiski
});
```

**ACID Garantijas:**
- **Atomicity:** Vai abi soļi izpildās, vai neviens
- **Consistency:** Datu integritāte tiek saglabāta
- **Isolation:** Citas transakcijas neredz nepabeigtus datus
- **Durability:** Committed izmaiņas ir permanent

#### 5.2.3 Eager Loading (N+1 Query Problem Risinājums)

```php
// NEOPTIMĀLI (N+1 queries)
$users = User::all();
foreach ($users as $user) {
    echo $user->loans->count();  // Katrs rada jaunu query
}

// OPTIMĀLI (2 queries: 1 for users, 1 for all loans)
$users = User::with('loans')->get();
foreach ($users as $user) {
    echo $user->loans->count();
}
```

### 5.3 Query Optimizācija

#### 5.3.1 Indeksu Izmantošana

```php
// Izmanto idx_isbn indeksu - O(log n)
$book = Book::where('isbn', '9780743273565')->first();

// Izmanto idx_title indeksu - O(log n + k)
$books = Book::where('title', 'LIKE', '%Gatsby%')->get();

// Composite indekss uz (user_id, return_date)
$activeLoans = Loan::where('user_id', $userId)
                   ->whereNull('return_date')
                   ->get();
```

#### 5.3.2 Query Builder Optimizācijas

```php
// Select tikai nepieciešamās kolonnas
$books = Book::select('id', 'title', 'author')->get();

// Chunk lielajiem datu kopumiem
Book::chunk(100, function ($books) {
    foreach ($books as $book) {
        // Process book
    }
});

// Pagination
$books = Book::paginate(20);  // Atgriež 20 grāmatas uz lappusi
```

---

## 6. Datu Integritātes Nodrošināšana

### 6.1 Validācija Aplikācijas Līmenī

```php
// Laravel Request Validation
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'isbn' => 'required|regex:/^\d{10}(\d{3})?$/|unique:books,isbn',
        'year' => 'required|integer|min:1000|max:' . date('Y'),
        'description' => 'nullable|string',
        'available' => 'boolean',
    ]);

    return Book::create($validated);
}
```

### 6.2 Datubāzes Līmeņa Ierobežojumi

**Migrācijās definēti:**

```php
Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->string('title');           // NOT NULL
    $table->string('author');          // NOT NULL
    $table->string('isbn')->unique();  // NOT NULL, UNIQUE
    $table->integer('year');           // NOT NULL
    $table->text('description')->nullable();
    $table->boolean('available')->default(true);
    $table->timestamps();
});

Schema::create('loans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('book_id')
          ->constrained('books')
          ->onDelete('cascade');       // Foreign Key Constraint
    $table->foreignId('user_id')
          ->constrained('users')
          ->onDelete('cascade');
    $table->date('loan_date');
    $table->date('due_date');
    $table->date('return_date')->nullable();
    $table->timestamps();
});
```

### 6.3 Business Rules Enforcement

```php
// Biznesa noteikums: maksimums 5 aktīvie aizdevumi
public function canBorrowBook(User $user): bool
{
    $activeLoans = Loan::where('user_id', $user->id)
                       ->whereNull('return_date')
                       ->count();

    return $activeLoans < 5;
}

// Biznesa noteikums: nevar aizņemties jau aizņemtu grāmatu
public function isBookAvailable(Book $book): bool
{
    return $book->available && 
           !Loan::where('book_id', $book->id)
                ->whereNull('return_date')
                ->exists();
}
```

---

## 7. Backup un Recovery Stratēģijas

### 7.1 Backup Metodes

#### 7.1.1 mysqldump (Loģiskā Backup)

**Pilna datubāzes backup:**
```bash
mysqldump -u root -p biblioteka > biblioteka_backup_2026_01_14.sql
```

**Tikai struktūra (bez datiem):**
```bash
mysqldump -u root -p --no-data biblioteka > schema_only.sql
```

**Tikai dati (bez struktūras):**
```bash
mysqldump -u root -p --no-create-info biblioteka > data_only.sql
```

**Compressed backup:**
```bash
mysqldump -u root -p biblioteka | gzip > biblioteka_backup.sql.gz
```

**Priekšrocības:**
- ✅ Portable (cross-platform)
- ✅ Human-readable SQL
- ✅ Selective restore

**Trūkumi:**
- ❌ Lēns lielajām datubāzēm
- ❌ Locks tabulas backup laikā

#### 7.1.2 Binary Backup (Physical Backup)

**Izmantojot MySQL Enterprise Backup vai Percona XtraBackup:**

```bash
# Percona XtraBackup
xtrabackup --backup --target-dir=/backups/2026-01-14
```

**Priekšrocības:**
- ✅ Ātrāks
- ✅ Online backup (nav downtime)
- ✅ Incremental backups

**Trūkumi:**
- ❌ Platform-specific
- ❌ Nav human-readable

#### 7.1.3 Binary Logs (Point-in-Time Recovery)

**Ieslēgt binary logging (`my.cnf`):**
```ini
[mysqld]
log-bin = /var/log/mysql/mysql-bin.log
expire_logs_days = 7
```

**Restore līdz konkrētam laikam:**
```bash
# 1. Restore full backup
mysql -u root -p biblioteka < biblioteka_backup.sql

# 2. Apply binary logs līdz specific timestamp
mysqlbinlog --stop-datetime="2026-01-14 10:00:00" mysql-bin.000001 | mysql -u root -p biblioteka
```

### 7.2 Automātiskā Backup Stratēģija

**Cron Job (Linux):**
```bash
# crontab -e
# Daily backup at 2 AM
0 2 * * * /usr/local/bin/backup_mysql.sh

# Weekly full backup every Sunday
0 3 * * 0 /usr/local/bin/weekly_backup.sh
```

**Backup Script (`backup_mysql.sh`):**
```bash
#!/bin/bash
DATE=$(date +%Y-%m-%d_%H-%M-%S)
BACKUP_DIR="/backups/mysql"
DB_NAME="biblioteka"

# Create backup
mysqldump -u root -p${DB_PASSWORD} ${DB_NAME} | gzip > ${BACKUP_DIR}/biblioteka_${DATE}.sql.gz

# Keep only last 7 days
find ${BACKUP_DIR} -name "biblioteka_*.sql.gz" -mtime +7 -delete

# Upload to cloud storage (optional)
aws s3 cp ${BACKUP_DIR}/biblioteka_${DATE}.sql.gz s3://my-bucket/backups/
```

### 7.3 Recovery Process

**Restore no mysqldump:**
```bash
# 1. Izveido jaunu datubāzi (ja nepieciešams)
mysql -u root -p -e "CREATE DATABASE biblioteka_restored;"

# 2. Restore no backup
mysql -u root -p biblioteka_restored < biblioteka_backup.sql

# 3. Verificē datus
mysql -u root -p -e "SELECT COUNT(*) FROM biblioteka_restored.books;"
```

**Disaster Recovery Soļi:**
1. Stop aplikāciju (novērst datu izmaiņas)
2. Restore pēdējais full backup
3. Apply binary logs (ja pieejami)
4. Verificē datu integritāti
5. Restart aplikāciju

---

## 8. Veiktspējas Monitorings

### 8.1 Query Performance

**Laravel Query Log:**
```php
// Enable query logging
DB::enableQueryLog();

// Izpildīt queries
$books = Book::where('available', true)->get();

// Skatīt logged queries
dd(DB::getQueryLog());
```

**Slow Query Log (`my.cnf`):**
```ini
[mysqld]
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow-query.log
long_query_time = 1  # Log queries taking > 1 second
```

### 8.2 EXPLAIN Analīze

```sql
EXPLAIN SELECT * FROM books WHERE isbn = '9780743273565';

-- Output:
+----+-------------+-------+-------+------------------+------+---------+-------+------+
| id | select_type | table | type  | possible_keys    | key  | key_len | ref   | rows |
+----+-------------+-------+-------+------------------+------+---------+-------+------+
|  1 | SIMPLE      | books | const | books_isbn_unique| isbn | 52      | const |    1 |
+----+-------------+-------+-------+------------------+------+---------+-------+------+
```

**Svarīgākie Lauki:**
- `type`: const (labākais), index, ALL (sliktākais)
- `possible_keys`: Indeksi, kas varētu tikt izmantoti
- `key`: Faktiskais izmantotais indekss
- `rows`: Aptuvens rindas skaits, kas tiks skanēts

---

## 9. Security Considerations

### 9.1 SQL Injection Prevention

**Laravel Eloquent/Query Builder automātiski izmanto prepared statements:**

```php
// DROŠI (auto-escaped)
$book = Book::where('isbn', $userInput)->first();

// DROŠI (named bindings)
$books = DB::select('SELECT * FROM books WHERE author = ?', [$author]);

// NEDROŠI (NEKAD nedarīt!)
$books = DB::select("SELECT * FROM books WHERE author = '$author'");
```

### 9.2 Database User Privileges

**Izveidot ierobežotu lietotāju:**
```sql
-- Production lietotājs ar ierobežotām tiesībām
CREATE USER 'biblioteka_app'@'localhost' IDENTIFIED BY 'secure_password';

GRANT SELECT, INSERT, UPDATE, DELETE ON biblioteka.* TO 'biblioteka_app'@'localhost';

-- NAV DROP, CREATE, ALTER tiesību (mazina risku)
```

### 9.3 Sensitive Data Encryption

```php
// Laravel Encryption
use Illuminate\Support\Facades\Crypt;

// Encrypt
$encrypted = Crypt::encryptString($sensitiveData);

// Decrypt
$decrypted = Crypt::decryptString($encrypted);

// Casts automātiski encrypt/decrypt
protected $casts = [
    'credit_card' => 'encrypted',
];
```

---

## 10. Kopsavilkums

### 10.1 Galīgais Lēmums

**Izvēlētā Glabāšanas Sistēma:** MySQL 8.0+ ar InnoDB engine

**Galvenās Priekšrocības:**
1. ✅ **ACID Transakcijas** - Datu integritāte garantēta
2. ✅ **Foreign Key Constraints** - Referential integrity
3. ✅ **B-Tree Indeksi** - O(log n) meklēšana
4. ✅ **MVCC Concurrency** - Vairāki lietotāji
5. ✅ **Backup/Recovery** - Vairākas metodes
6. ✅ **Mērogojamība** - Līdz 100,000+ ieraksti
7. ✅ **Laravel ORM** - Vienkārša integrācija
8. ✅ **Industrijas Standarts** - Plaši atbalstīts

### 10.2 Arhitektūras Pārskats

```
┌──────────────────────────────────────────┐
│       Laravel Application Layer          │
│  - Business Logic                        │
│  - Validation                            │
│  - Transaction Management                │
├──────────────────────────────────────────┤
│       Eloquent ORM Layer                 │
│  - Model Definitions                     │
│  - Relationships                         │
│  - Query Builder                         │
├──────────────────────────────────────────┤
│       Database Abstraction (PDO)         │
│  - Prepared Statements                   │
│  - Connection Pooling                    │
├──────────────────────────────────────────┤
│       MySQL Server (InnoDB)              │
│  - ACID Transactions                     │
│  - B-Tree Indexes                        │
│  - Foreign Keys                          │
│  - Binary Logs                           │
└──────────────────────────────────────────┘
         ↓
┌──────────────────────────────────────────┐
│       Persistent Storage                 │
│  - Data Files (.ibd)                     │
│  - Index Files                           │
│  - Binary Logs                           │
│  - Backup Files                          │
└──────────────────────────────────────────┘
```

### 10.3 Key Metrics

| Metrika | Vērtība | Piezīmes |
|---------|---------|----------|
| Read Latency | < 1ms | Ar indeksiem |
| Write Latency | < 5ms | Ar transakcijām |
| Max Concurrent Users | 100+ | Ar connection pooling |
| Max Records | 1M+ | Bez degradācijas |
| Backup Duration | < 5min | mysqldump 10K records |
| Recovery Time | < 10min | Full restore |

---

**Dokumenta versija:** 1.0  
**Pēdējā atjaunināšana:** 2026-01-14
