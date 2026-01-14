# Konceptuālais Datu Modelis (ER Diagramma)

## 1. Entītijas un To Apraksts

### 1.1 Entītija: USER (Lietotājs)

**Apraksts:** Attēlo sistēmas lietotāju, kas var būt administrators, bibliotekārs vai parasts lasītājs.

**Atribūti:**
- `id` (PK): Unikāls lietotāja identifikators (INTEGER, AUTO_INCREMENT)
- `name`: Lietotāja pilnais vārds (VARCHAR(255))
- `email`: E-pasta adrese (VARCHAR(255), UNIQUE)
- `password`: Šifrēta parole (VARCHAR(255))
- `role`: Lietotāja loma (ENUM: 'admin', 'librarian', 'user')
- `created_at`: Reģistrācijas datums (TIMESTAMP)
- `updated_at`: Pēdējās izmaiņas datums (TIMESTAMP)

**Biznesa Noteikumi:**
- E-pasta adresei jābūt unikālai
- Parolei jābūt vismaz 8 simbolus garai
- Noklusējuma loma ir 'user'
- Lietotājs var tikt izdzēsts tikai tad, ja nav aktīvu aizdevumu

---

### 1.2 Entītija: BOOK (Grāmata)

**Apraksts:** Attēlo bibliotēkas grāmatu ar visiem tās atribūtiem.

**Atribūti:**
- `id` (PK): Unikāls grāmatas identifikators (INTEGER, AUTO_INCREMENT)
- `title`: Grāmatas nosaukums (VARCHAR(255))
- `author`: Autora vārds (VARCHAR(255))
- `isbn`: Starptautiskais standarta grāmatas numurs (VARCHAR(13), UNIQUE)
- `genre`: Grāmatas žanrs (VARCHAR(100), NULLABLE)
- `image`: Vāka attēla ceļš (VARCHAR(255), NULLABLE)
- `year`: Izdošanas gads (INTEGER, NULLABLE)
- `publisher`: Izdevējs (VARCHAR(255), NULLABLE)
- `pages`: Lappušu skaits (INTEGER, NULLABLE)
- `language`: Valoda (VARCHAR(50), DEFAULT 'Latvian')
- `description`: Grāmatas apraksts (TEXT, NULLABLE)
- `available`: Pieejamības statuss (BOOLEAN, DEFAULT TRUE)
- `created_at`: Pievienošanas datums (TIMESTAMP)
- `updated_at`: Pēdējās izmaiņas datums (TIMESTAMP)

**Biznesa Noteikumi:**
- ISBN ir unikāls un obligāts
- Nosaukums un autors ir obligāti lauki
- Grāmata ir pieejama, ja nav aktīvu aizdevumu
- Grāmatu var dzēst tikai administrators

---

### 1.3 Entītija: LOAN (Aizdevums)

**Apraksts:** Attēlo grāmatas aizņemšanās faktu - saista lietotāju ar grāmatu laika periodā.

**Atribūti:**
- `id` (PK): Unikāls aizdevuma identifikators (INTEGER, AUTO_INCREMENT)
- `user_id` (FK): Atsauce uz lietotāju (INTEGER, NOT NULL)
- `book_id` (FK): Atsauce uz grāmatu (INTEGER, NOT NULL)
- `borrowed_at`: Aizņemšanās datums un laiks (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- `due_date`: Atgriešanas termiņš (DATE, NOT NULL)
- `returned_at`: Faktiskais atgriešanas datums (TIMESTAMP, NULLABLE)
- `created_at`: Ieraksta izveidošanas datums (TIMESTAMP)
- `updated_at`: Pēdējās izmaiņas datums (TIMESTAMP)

**Biznesa Noteikumi:**
- Viens lietotājs nevar aizņemties vienu un to pašu grāmatu vairākas reizes vienlaicīgi
- Atgriešanas termiņš ir 14 dienas no aizņemšanās datuma
- Ja `returned_at` ir NULL, aizdevums ir aktīvs
- Viens lietotājs var aizņemties maksimums 5 grāmatas vienlaicīgi

---

## 2. Saites Starp Entītijām

### 2.1 USER ↔ LOAN (1:N saite)

**Saites Tips:** Viens pret daudziem (One-to-Many)

**Apraksts:** Viens lietotājs var veikt daudzus aizdevumus laika gaitā, bet katrs aizdevums pieder tikai vienam lietotājam.

**Kardinalitāte:**
- USER puse: 1 (viena)
- LOAN puse: N (daudzi)

**Obligātums:**
- Lietotājam nav obligāti jābūt aizdevumiem (lietotājs var eksistēt bez aizdevumiem)
- Katram aizdevumam obligāti jābūt lietotājam

**Īstenošana:**
- `LOAN` tabulā ir ārējā atslēga `user_id`, kas atsaucas uz `USER.id`
- Izdzēšot lietotāju, var:
  - **RESTRICT**: Neļaut dzēst, ja ir aizdevumi
  - **CASCADE**: Dzēst visus lietotāja aizdevumus (izmantots projektā)
  - **SET NULL**: Nav piemērojams (user_id ir NOT NULL)

**SQL Ierobežojums:**
```sql
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
```

---

### 2.2 BOOK ↔ LOAN (1:N saite)

**Saites Tips:** Viens pret daudziem (One-to-Many)

**Apraksts:** Viena grāmata var būt aizņemta daudzas reizes (dažādos laika periodos), bet katrs aizdevums attiecas uz vienu konkrētu grāmatu.

**Kardinalitāte:**
- BOOK puse: 1 (viena)
- LOAN puse: N (daudzi)

**Obligātums:**
- Grāmatai nav obligāti jābūt aizdevumiem (grāmata var eksistēt bez aizdevumiem)
- Katram aizdevumam obligāti jābūt grāmatai

**Īstenošana:**
- `LOAN` tabulā ir ārējā atslēga `book_id`, kas atsaucas uz `BOOK.id`
- Izdzēšot grāmatu:
  - **RESTRICT**: Neļaut dzēst, ja ir aizdevumu vēsture (izmantots projektā)
  - Grāmatas vēsture tiek saglabāta auditācijas nolūkos

**SQL Ierobežojums:**
```sql
FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE RESTRICT
```

---

## 3. ER Diagrammas Attēlojums

### 3.1 Grafiskais Attēlojums

```
┌─────────────────┐         ┌──────────────────┐         ┌─────────────────┐
│     USER        │         │      LOAN        │         │      BOOK       │
├─────────────────┤         ├──────────────────┤         ├─────────────────┤
│ PK: id          │1       N│ PK: id           │N       1│ PK: id          │
│ name            ├─────────┤ FK: user_id      │─────────┤ title           │
│ email (UNIQUE)  │         │ FK: book_id      │         │ author          │
│ password        │         │ borrowed_at      │         │ isbn (UNIQUE)   │
│ role            │         │ due_date         │         │ genre           │
│ created_at      │         │ returned_at      │         │ image           │
│ updated_at      │         │ created_at       │         │ year            │
└─────────────────┘         │ updated_at       │         │ publisher       │
                            └──────────────────┘         │ pages           │
                                                         │ language        │
                                                         │ description     │
                                                         │ available       │
                                                         │ created_at      │
                                                         │ updated_at      │
                                                         └─────────────────┘
```

**Saišu Apraksts:**
- USER → LOAN: "veic" (1 lietotājs veic N aizdevumus)
- BOOK → LOAN: "ir aizņemta" (1 grāmata ir aizņemta N reizes)

### 3.2 Chen Notācijas Attēlojums

```
       aizņemas                  attiecas uz
USER ──────────── LOAN ──────────────── BOOK
 1                M:N                      1
```

**Piezīme:** Faktiskā implementācija ir divas 1:N saites caur LOAN entītiju (association table).

---

## 4. Kardinalitātes Detalizēts Apraksts

### 4.1 Kardinalitātes Simboli

- **1** : Tieši viens
- **N** vai **M** : Daudzi (0 vai vairāk)
- **0..1** : Nulle vai viens (optional)
- **1..N** : Viens vai vairāki

### 4.2 Saišu Kardinalitāte

| Saite | Minimālā Kardinalitāte | Maksimālā Kardinalitāte | Obligātums |
|-------|----------------------|----------------------|------------|
| USER → LOAN | 0 | N | Nav obligāts |
| LOAN → USER | 1 | 1 | Obligāts |
| BOOK → LOAN | 0 | N | Nav obligāts |
| LOAN → BOOK | 1 | 1 | Obligāts |

---

## 5. Biznesa Noteikumi un Ierobežojumi

### 5.1 Integritātes Ierobežojumi

1. **Entity Integrity (Entītijas Integritāte):**
   - Katrai entītijai ir primārā atslēga (PK)
   - Primārā atslēga nevar būt NULL
   - Primārā atslēga ir unikāla

2. **Referential Integrity (Atsauču Integritāte):**
   - Katrai ārējai atslēgai (FK) jāatsaucas uz eksistējošu primāro atslēgu
   - Nedrīkst būt "karājas" atsauces (orphaned references)

3. **Domain Integrity (Domēna Integritāte):**
   - Email formāts jāvalidē
   - ISBN jābūt 10 vai 13 ciparu formātā
   - Datumi jābūt loģiski (due_date > borrowed_at)

### 5.2 Biznesa Loģikas Ierobežojumi

1. **Grāmatas Pieejamība:**
   ```
   book.available = (COUNT(active_loans WHERE book_id = book.id) == 0)
   ```

2. **Lietotāja Aizdevumu Limits:**
   ```
   COUNT(active_loans WHERE user_id = user.id) <= 5
   ```

3. **Aizdevuma Termiņš:**
   ```
   due_date = borrowed_at + INTERVAL 14 DAY
   ```

4. **Aktīvais Aizdevums:**
   ```
   is_active = (returned_at IS NULL)
   ```

---

## 6. Normalizācija

### 6.1 Pirmā Normālforma (1NF)
✅ **Izpildīta:** Visas tabulas satur tikai atomārus vērtības. Nav atkārtotu grupu vai masīvu.

### 6.2 Otrā Normālforma (2NF)
✅ **Izpildīta:** Visas ne-atslēgu kolonnas ir pilnībā atkarīgas no primārās atslēgas. Nav daļēju atkarību.

### 6.3 Trešā Normālforma (3NF)
✅ **Izpildīta:** Nav tranzitīvu atkarību. Visas ne-atslēgu kolonnas ir atkarīgas tikai no primārās atslēgas, nevis no citām ne-atslēgu kolonnām.

**Piemērs:** `book.available` varētu būt atkarīgs no aktīvajiem aizdevumiem (transitīva atkarība), bet mēs to uzturām kā aprēķinātu lauku veiktspējas uzlabošanai (denormalizācija ar pamatojumu).

---

## 7. Paplašinājumi un Turpmākā Attīstība

### 7.1 Potenciālās Jaunās Entītijas

1. **RESERVATION (Rezervācija):**
   - Ļautu lietotājiem rezervēt aizņemtas grāmatas
   - Saites: USER 1:N RESERVATION, BOOK 1:N RESERVATION

2. **REVIEW (Atsauksme):**
   - Lietotāji var novērtēt un komentēt grāmatas
   - Saites: USER 1:N REVIEW, BOOK 1:N REVIEW

3. **CATEGORY (Kategorija):**
   - Hierarhiska grāmatu kategoriju struktūra
   - Saites: CATEGORY M:N BOOK

4. **FINE (Soda Nauda):**
   - Nokavētu aizdevumu soda naudas
   - Saites: LOAN 1:1 FINE, USER 1:N FINE

### 7.2 Paplašinātas Saites

1. **BOOK ↔ AUTHOR (M:N):**
   - Grāmatai var būt vairāki autori
   - Autoram var būt vairākas grāmatas
   - Caur association table: BOOK_AUTHOR

2. **BOOK ↔ CATEGORY (M:N):**
   - Grāmata var piederēt vairākām kategorijām
   - Kategorijai ir daudzas grāmatas

---

## 8. ER Diagrammas Vizualizācija (Graphviz DOT)

ER diagramma ir izveidota izmantojot Graphviz DOT notāciju un atrodama failā: [`er_diagram.dot`](../er_diagram.dot)

### 8.1 Diagrammas Ģenerēšana

**PNG attēla ģenerēšana:**
```bash
dot -Tpng er_diagram.dot -o er_diagram.png
```

**SVG attēla ģenerēšana:**
```bash
dot -Tsvg er_diagram.dot -o er_diagram.svg
```

**PDF attēla ģenerēšana:**
```bash
dot -Tpdf er_diagram.dot -o er_diagram.pdf
```

---

## 9. Kopsavilkums

Konceptuālais datu modelis definē trīs galvenās entītijas:
1. **USER** - Sistēmas lietotāji ar lomām
2. **BOOK** - Bibliotēkas grāmatu katalogs
3. **LOAN** - Aizdevumi, kas saista lietotājus ar grāmatām

Modelis izmanto klasisko bibliotēkas domēna pieeju ar skaidrām 1:N saitēm starp entītijām. Modelis ir normalizēts līdz 3NF un atbalsta visas galvenās bibliotēkas biznesa operācijas.

**Priekšrocības:**
- Vienkārša un intuitīva struktūra
- Viegli paplašināma
- Atbalsta visas funkcionālās prasības
- Nodrošina datu integritāti

**Dokumenta versija:** 1.0  
**Pēdējā atjaunināšana:** 2026-01-14
