# Bibliotēkas Pārvaldības Sistēma

> **Projekts:** Datu struktūru un datu glabāšanas sistēmas izstrāde nelielai bibliotēkai  
> **Versija:** 1.0  
> **Datums:** 2026-01-14

Pilnvērtīga bibliotēkas pārvaldības sistēma ar optimizētām datu struktūrām un ACID atbilstošu datubāzi.

---

## Pilnīga Dokumentācija

**GALVENAIS DOKUMENTS:**

### [PROJEKTA_DOKUMENTACIJA.md](PROJEKTA_DOKUMENTACIJA.md)

Pilnīga tehniskā dokumentācija ar visiem risinājumiem un analīzi.

---

## Dokumentācijas Struktūra

| Dokuments | Apraksts |
|-----------|----------|
| **[PROJEKTA_DOKUMENTACIJA.md](PROJEKTA_DOKUMENTACIJA.md)** | Galvenais dokuments ar visiem kritērijiem |
| [docs/PRASIBAS.md](docs/PRASIBAS.md) | Prasību dokuments |
| [docs/KONCEPTUALAIS_MODELIS.md](docs/KONCEPTUALAIS_MODELIS.md) | ER diagramma un analīze |
| [docs/LOGISKAIS_MODELIS.md](docs/LOGISKAIS_MODELIS.md) | Tabulu shēmas |
| [docs/DATU_STRUKTURAS.md](docs/DATU_STRUKTURAS.md) | Datu struktūru izvēle un pamatojums |
| [docs/GLABASHANAS_SISTEMA.md](docs/GLABASHANAS_SISTEMA.md) | Glabāšanas sistēmas izvēle |

---

## Projekta Kopsavilkums

### Galvenās Iezīmes

- **Hash Table datu struktūra** ar O(1) ISBN meklēšanu
- **MySQL datubāze** ar pilnu ACID atbalstu
- **Laravel Eloquent ORM** datu persistencei
- **Optimizēti B-Tree indeksi** ātrākai meklēšanai
- **Foreign key constraints** datu integritātei
- **Transakciju atbalsts** kritiskām operācijām
- **Backup stratēģijas** (mysqldump, binary logs)

### Tehnoloģijas

- **Backend:** Laravel 12.47, PHP 8.2+
- **Database:** MySQL 8.0+ (InnoDB engine)
- **Frontend:** Tailwind CSS 4.0, Vite 7.0
- **ORM:** Eloquent
- **Data Structures:** Hash Table (PHP associative arrays)

---

## Ātrā Uzstādīšana

```bash
# 1. Instalē atkarības
composer install
npm install

# 2. Konfigurē vidi
cp .env.example .env
php artisan key:generate

# 3. Izveido datubāzi (rediģē .env vispirms)
mysql -u root -p -e "CREATE DATABASE biblioteka;"
php artisan migrate

# 4. Build assets
npm run build

# 5. Palaiž serveri
php artisan serve
```

**Aplikācija:** http://localhost:8000

---

## Projekta Struktūra

```
bibliot-eka/
├── PROJEKTA_DOKUMENTACIJA.md   # GALVENAIS DOKUMENTS
├── README.md                    # Šis fails
├── er_diagram.dot               # ER diagramma (Graphviz)
├── docs/                        # Detalizēta dokumentācija
│   ├── PRASIBAS.md
│   ├── KONCEPTUALAIS_MODELIS.md
│   ├── LOGISKAIS_MODELIS.md
│   ├── DATU_STRUKTURAS.md
│   └── GLABASHANAS_SISTEMA.md
├── app/
│   ├── DataStructures/             # In-memory struktūras
│   │   ├── Book.php               # Grāmatas klase
│   │   └── Library.php            # Hash table implementācija
│   ├── Models/                     # Eloquent modeli
│   │   ├── Book.php
│   │   ├── User.php
│   │   └── Loan.php
│   └── Http/Controllers/          # API kontrolieri
├── database/
│   └── migrations/                 # Datubāzes shēmas
└── resources/
    └── views/                      # Blade templates
```

---

## Galvenie Sasniegumi

### Prasību Analīze
- Pilnīgs funkcionālo prasību saraksts (FR-01 līdz FR-08)
- Nefunkcionālās prasības (veiktspēja, drošība, uzticamība)
- Lietotāju lomas (Administrator, Bibliotekārs, Lietotājs, Viesis)
- Prioritātes un pieņēmumi

### Datu Modelēšana
- ER diagramma ar 3 entītijām (USER, BOOK, LOAN)
- Pareizi definētas 1:N saites
- Tabulu shēmas ar visiem laukiem un tipiem
- Foreign key constraints
- 15+ optimizēti indeksi

### Datu Struktūras
- **Hash Table izvēle** - O(1) ISBN meklēšana
- Detalizēts salīdzinājums ar 7 alternatīvām
- Big O analīze visām operācijām
- Atmiņas izmantošanas analīze

### Glabāšanas Sistēma
- **MySQL izvēle** ar ACID garantijām
- Salīdzinājums ar 5 alternatīvām (CSV, SQLite, PostgreSQL, MongoDB, Redis)
- Eloquent ORM integrācija
- Backup stratēģijas (mysqldump, binary logs)
- Transaction support

---

## Veiktspējas Metriki

| Operācija | Laiks | Kompleksitāte |
|-----------|-------|---------------|
| ISBN meklēšana | 0.001 ms | O(1) |
| Grāmatas pievienošana | 0.02 ms | O(1) |
| Grāmatas dzēšana | 0.001 ms | O(1) |
| Meklēšana pēc nosaukuma | 12 ms (10K) | O(n) |
| Datubāzes query (ar indeksu) | < 1 ms | O(log n) |

**Atmiņas izmantošana:**
- 10,000 grāmatas: ~5.2 MB
- 100,000 grāmatas: ~52 MB

---

## Papildus Informācija

### ER Diagrammas Ģenerēšana

```bash
# PNG
dot -Tpng er_diagram.dot -o er_diagram.png

# SVG
dot -Tsvg er_diagram.dot -o er_diagram.svg
```

### Datubāzes Backup

```bash
# Backup
mysqldump -u root -p biblioteka > backup.sql

# Restore
mysql -u root -p biblioteka < backup.sql
```

---

**Autors:** Darkwizard  
**Gads:** 2026  
**Licence:** MIT
