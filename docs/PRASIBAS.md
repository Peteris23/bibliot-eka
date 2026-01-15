# Prasību Dokuments: Bibliotēkas Pārvaldības Sistēma

## 1. Projekta Apraksts

Bibliotēkas pārvaldības sistēma ir web-bāzēta aplikācija, kas paredzēta nelielas bibliotēkas darbības automatizācijai. Sistēma ļauj pārvaldīt grāmatas, lietotājus un grāmatu aizņemšanās/atgriešanas procesus.

## 2. Lietotāju Lomas un Tiesības

### 2.1 Administrators
- **Pilnas piekļuves tiesības:**
  - Grāmatu CRUD operācijas (Create, Read, Update, Delete)
  - Lietotāju pārvaldība
  - Visu aizdevumu skatīšana un pārvaldība
  - Sistēmas statistikas skatīšana
  - Datu eksportēšana un importēšana

### 2.2 Bibliotekārs
- **Ierobežotas pārvaldības tiesības:**
  - Grāmatu reģistrācija un rediģēšana
  - Aizdevumu reģistrācija un atgriešana
  - Grāmatu pieejamības statusa atjaunināšana
  - Lietotāju meklēšana un skatīšana

### 2.3 Reģistrēts Lietotājs
- **Pamata funkcionalitāte:**
  - Grāmatu kataloga pārlūkošana
  - Grāmatu meklēšana pēc dažādiem kritērijiem
  - Savu aizdevumu vēstures skatīšana
  - Grāmatu rezervēšana
  - Profila datu rediģēšana

### 2.4 Viesis (Nav autentificēts)
- **Tikai skatīšanās tiesības:**
  - Publiskā grāmatu kataloga skatīšana
  - Grāmatu meklēšana (ierobežota)
  - Informācija par bibliotēku

## 3. Funkcionālās Prasības

### FR-01: Lietotāju Autentifikācija un Autorizācija
**Prioritāte:** Augsta  
**Apraksts:** Sistēma nodrošina droša lietotāju pieteikšanās un reģistrācijas mehānismu.

- FR-01.1: Lietotājs var reģistrēties ar e-pastu un paroli
- FR-01.2: Lietotājs var pieteikties sistēmā
- FR-01.3: Lietotājs var atjaunot aizmirstu paroli
- FR-01.4: Sistēma pārbauda lietotāja lomu un piešķir atbilstošas tiesības
- FR-01.5: Paroles tiek šifrētas (bcrypt/Argon2)

### FR-02: Grāmatu Pārvaldība
**Prioritāte:** Augsta  
**Apraksts:** Administrators un bibliotekārs var pārvaldīt grāmatu katalogu.

- FR-02.1: Pievienot jaunu grāmatu ar atribūtiem:
  - Nosaukums (obligāts)
  - Autors (obligāts)
  - ISBN (unikāls, obligāts)
  - Žanrs
  - Izdošanas gads
  - Izdevējs
  - Lappušu skaits
  - Valoda
  - Apraksts
  - Attēls
- FR-02.2: Rediģēt esošās grāmatas informāciju
- FR-02.3: Dzēst grāmatu no sistēmas
- FR-02.4: Atzīmēt grāmatu kā pieejamu/aizņemtu
- FR-02.5: Augšupielādēt grāmatas vāka attēlu

### FR-03: Grāmatu Meklēšana
**Prioritāte:** Augsta  
**Apraksts:** Visi lietotāji var meklēt grāmatas pēc dažādiem kritērijiem.

- FR-03.1: Meklēšana pēc nosaukuma (daļēja sakritība)
- FR-03.2: Meklēšana pēc autora vārda
- FR-03.3: Meklēšana pēc ISBN
- FR-03.4: Meklēšana pēc žanra
- FR-03.5: Filtrēšana pēc pieejamības statusa
- FR-03.6: Filtrēšana pēc izdošanas gada
- FR-03.7: Kārtošana pēc dažādiem laukiem (nosaukums, autors, gads)

### FR-04: Aizdevumu Pārvaldība
**Prioritāte:** Augsta  
**Apraksts:** Sistēma ļauj reģistrēt un pārvaldīt grāmatu aizņemšanos.

- FR-04.1: Reģistrēt jaunu aizdevumu:
  - Lietotāja ID
  - Grāmatas ID
  - Aizņemšanās datums (automātiski)
  - Atgriešanas termiņš (14 dienas)
- FR-04.2: Reģistrēt grāmatas atgriešanu
- FR-04.3: Skatīt aktīvos aizdevumus
- FR-04.4: Skatīt aizdevumu vēsturi
- FR-04.5: Pagarināt aizdevuma termiņu (ja nav rezervēts)
- FR-04.6: Aprēķināt nokavējuma maksas (ja piemērojams)

### FR-05: Lietotāju Profils
**Prioritāte:** Vidēja  
**Apraksts:** Lietotāji var pārvaldīt savu profilu.

- FR-05.1: Skatīt savu profila informāciju
- FR-05.2: Rediģēt vārdu un kontaktinformāciju
- FR-05.3: Mainīt paroli
- FR-05.4: Skatīt savu aizdevumu vēsturi
- FR-05.5: Skatīt aktīvos aizdevumus un atlikušos datumus

### FR-06: Grāmatu Rezervēšana
**Prioritāte:** Vidēja  
**Apraksts:** Lietotāji var rezervēt aizņemtas grāmatas.

- FR-06.1: Rezervēt grāmatu, ja tā ir aizņemta
- FR-06.2: Paziņojums, kad grāmata kļūst pieejama
- FR-06.3: Rezervācijas rindas pārvaldība (FIFO)
- FR-06.4: Rezervācijas derīguma termiņš (48 stundas)

### FR-07: Statistika un Pārskati
**Prioritāte:** Zema  
**Apraksts:** Administrators var skatīt sistēmas statistiku.

- FR-07.1: Kopējais grāmatu skaits
- FR-07.2: Aktīvo aizdevumu skaits
- FR-07.3: Reģistrēto lietotāju skaits
- FR-07.4: Populārākās grāmatas
- FR-07.5: Nokavētie aizdevumi
- FR-07.6: Eksportēt datus (CSV, PDF)

### FR-08: Datu Persistences
**Prioritāte:** Augsta  
**Apraksts:** Visi dati tiek saglabāti datubāzē un ir pieejami starp sesijām.

- FR-08.1: Automātiska datu saglabāšana MySQL datubāzē
- FR-08.2: Transakciju atbalsts kritiskām operācijām
- FR-08.3: Datu integritātes pārbaudes (foreign keys, constraints)
- FR-08.4: Regulāras datubāzes rezerves kopijas (backup)

## 4. Nefunkcionālās Prasības

### NFR-01: Veiktspēja
- NFR-01.1: Grāmatu meklēšana pēc ISBN: O(1) vidējais laiks
- NFR-01.2: Lapa ielādējas mazāk nekā 2 sekundēs
- NFR-01.3: Sistēma atbalsta vismaz 1000 vienlaicīgus lietotājus
- NFR-01.4: Datubāzes vaicājumi optimizēti ar indeksiem

### NFR-02: Drošība
- NFR-02.1: Visu paroļu šifrēšana ar bcrypt
- NFR-02.2: CSRF aizsardzība visām formām
- NFR-02.3: SQL injection aizsardzība (izmantojot prepared statements)
- NFR-02.4: XSS aizsardzība (input sanitization)
- NFR-02.5: HTTPS protokols produkcionālajā vidē

### NFR-03: Lietojamība
- NFR-03.1: Responsīvs dizains (mobile-first)
- NFR-03.2: Intuitīva lietotāja saskarne
- NFR-03.3: Pieejamība (WCAG 2.1 Level AA)
- NFR-03.4: Latīņu valodas atbalsts
- NFR-03.5: Skaidri kļūdu paziņojumi

### NFR-04: Uzticamība
- NFR-04.1: 99.5% uptime
- NFR-04.2: Automātiska kļūdu reģistrēšana (logging)
- NFR-04.3: Graceful error handling
- NFR-04.4: Regulāras datubāzes backups

### NFR-05: Mērogojamība
- NFR-05.1: Modulāra arhitektūra
- NFR-05.2: Iespēja pievienot jaunas funkcionalitātes
- NFR-05.3: Datubāze var palielināties līdz 100,000+ ierakstiem
- NFR-05.4: Horizontal scaling iespējas

### NFR-06: Uzturēšana
- NFR-06.1: Komentēts kods (PHPDoc)
- NFR-06.2: Vienību testi kritiskajām funkcijām
- NFR-06.3: Dokumentācija izstrādātājiem
- NFR-06.4: Versiiju kontrole (Git)

## 5. Sistēmas Ierobežojumi

### 5.1 Tehniskie Ierobežojumi
- Sistēma izstrādāta ar Laravel 12 (PHP 8.2+)
- Datubāze: MySQL 8.0+
- Frontend: Tailwind CSS 4.0, Vite
- Minimālās servera prasības:
  - PHP 8.2+
  - MySQL 8.0+
  - 2GB RAM
  - 10GB brīva vieta

### 5.2 Biznesa Ierobežojumi
- Viens lietotājs var aizņemt maksimums 5 grāmatas vienlaicīgi
- Aizdevuma periods: 14 dienas
- Rezervācijas derīgums: 48 stundas
- ISBN ir unikāls identifikators grāmatām

## 6. Pieņēmumi

1. Lietotāji ir pazīstami un uzticami (nav nepieciešama sarežģīta verifikācija)
2. Grāmatas katalogs sākotnēji ir mazāks par 10,000 grāmatām
3. Bibliotēka darbojas standarta darba laikā
4. Maksājumu apstrādes sistēma nav nepieciešama šajā versijā
5. Sistēma ir paredzēta vienai bibliotēkai (nav multi-tenancy)

## 7. Atkarības

1. **Ārējie Servisi:**
   - E-pasta pakalpojums paziņojumu sūtīšanai (SMTP)
   - Attēlu glabāšanas serveris

2. **Trešo Pušu Bibliotēkas:**
   - Laravel Framework
   - Composer (PHP dependency manager)
   - NPM (Node.js dependency manager)

## 8. Prioritāšu Saraksts

### Augsta Prioritāte (Must Have)
- Lietotāju autentifikācija (FR-01)
- Grāmatu CRUD (FR-02)
- Grāmatu meklēšana (FR-03)
- Aizdevumu pārvaldība (FR-04)
- Datu persistences (FR-08)

### Vidēja Prioritāte (Should Have)
- Lietotāju profils (FR-05)
- Grāmatu rezervēšana (FR-06)
- Paziņojumi par termiņiem

### Zema Prioritāte (Nice to Have)
- Statistika un pārskati (FR-07)
- Eksportēšanas funkcionalitāte
- Attīstīta meklēšana ar filtriem

## 9. Riska Analīze

| Risks | Ietekme | Varbūtība | Mazināšanas Stratēģija |
|-------|---------|-----------|------------------------|
| Datu zudums | Augsta | Zema | Regulāras backups, transakcijas |
| Drošības pārkāpums | Augsta | Vidēja | Laravel drošības features, regulāri atjauninājumi |
| Veiktspējas problēmas | Vidēja | Vidēja | Datubāzes indeksi, kešošana |
| Lietotāju pieņemšana | Vidēja | Zema | Lietojamības testēšana, apmācība |

## 10. Izstrādes Grafiks

1. **Fāze 1 (2 nedēļas):** Autentifikācija, pamata UI
2. **Fāze 2 (3 nedēļas):** Grāmatu CRUD, meklēšana
3. **Fāze 3 (2 nedēļas):** Aizdevumu sistēma
4. **Fāze 4 (1 nedēļa):** Rezervēšana, profili
5. **Fāze 5 (1 nedēļa):** Testēšana, optimizācija

---

**Dokumenta versija:** 1.0  
**Pēdējā atjaunināšana:** 2026-01-14  
**Autors:** Bibliotēkas Sistēmas Izstrādes Komanda
