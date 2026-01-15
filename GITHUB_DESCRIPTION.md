# Bibliotēkas Pārvaldības Sistēma

Pilnvērtīga datu struktūru un glabāšanas sistēmas izstrāde nelielai bibliotēkai

## Par Projektu

Bibliotēkas pārvaldības sistēma izstrādāta kā universitātes darbs, kas demonstrē:
- Efektīvu datu struktūru izmantošanu (Hash Table ar O(1) meklēšanu)
- Relāciju datubāzes dizainu (MySQL ar ACID garantijām)
- Laravel web aplikācijas izstrādi
- Pilnu tehnisko dokumentāciju ar Big O analīzi

## Galvenās Iezīmes

- **Hash Table datu struktūra** - O(1) ISBN meklēšana
- **MySQL 8.0+** - ACID transakcijas, Foreign Keys
- **Laravel 12** - Eloquent ORM, migrācijas
- **Optimizēti B-Tree indeksi** - O(log n) vaicājumi
- **Pilnīga tehniskā dokumentācija** - Detalizēta analīze

## Dokumentācija

**Galvenais dokuments:** [PROJEKTA_DOKUMENTACIJA.md](PROJEKTA_DOKUMENTACIJA.md)

Detalizēta dokumentācija:
- [Prasību Dokuments](docs/PRASIBAS.md) - Funkcionālās un nefunkcionālās prasības
- [Konceptuālais Modelis](docs/KONCEPTUALAIS_MODELIS.md) - ER diagramma ar 3 entītijām
- [Loģiskais Modelis](docs/LOGISKAIS_MODELIS.md) - Tabulu shēmas ar indeksiem
- [Datu Struktūras](docs/DATU_STRUKTURAS.md) - Hash Table izvēle un pamatojums
- [Glabāšanas Sistēma](docs/GLABASHANAS_SISTEMA.md) - MySQL izvēle un implementācija

## Uzstādīšana

```bash
composer install && npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

## Tehnoloģijas

- PHP 8.2+ | Laravel 12 | MySQL 8.0+
- Eloquent ORM | Tailwind CSS 4 | Vite 7
- Hash Table | B-Tree Indexes | ACID Transactions

---

**Autors:** Darkwizard  
**Gads:** 2026  
**Licence:** MIT
