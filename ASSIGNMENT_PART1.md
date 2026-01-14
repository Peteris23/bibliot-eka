# Bibliotēkas Grāmatu Pārvaldības Sistēma - 1. Daļa

## Datu Struktūru Analīze

### A) Grāmatu katalogs

**Izvēlētā datu struktūra:** Masīvs (Array)

**Pamatojums:**
- **Laika sarežģītība:** O(1) - tiešā piekļuve pēc indeksa (ID)
- **Fiksēts izmērs:** Grāmatu skaits nemainās (5000), tāpēc masīva fiksētais izmērs nav trūkums
- **Efektīva atmiņas izmantošana:** Grāmatas ar ID 1-5000 var tikt glabātas masīvā, kur indekss = ID
- **Ātra meklēšana:** book = catalog[bookId] nodrošina momentānu piekļuvi

**Alternatīvie risinājumi:**
- **Saistītais saraksts:** Būtu neefektīvs - O(n) laika sarežģītība meklēšanai. Nepieciešams pārlūkot visu sarakstu, lai atrastu grāmatu pēc ID
- **Jaucējtabula (Hash Table):** Varētu izmantot, bet nav nepieciešama, jo ID ir secīgi skaitļi - masīvs ir vienkāršāks un tikpat ātrs

**Ja grāmatu skaits mainītos:**
- Masīvs vairs nebūtu optimāls, jo tā izmēra maiņa ir dārga operācija
- **Labāka izvēle:** Jaucējtabula (HashMap/Dictionary) - O(1) piekļuve, dinamisks izmērs
- **Alternatīva:** Saistītais saraksts ar jaucējtabulu indeksēšanai
- **Praktiskā izvēle:** Datubāze ar indeksiem uz ID lauku

---

### B) Rezervāciju saraksts populārai grāmatai

**Izvēlētā datu struktūra:** Rinda (Queue - FIFO)

**Pamatojums:**
- **FIFO princips:** "First In, First Out" - pirmais rindā, pirmais saņem grāmatu
- **Godīgums:** Nodrošina taisnīgu kārtību - kas pirms piesakās, tas pirms saņem
- **Efektīvas operācijas:** 
  - Pievienošana (enqueue): O(1) - jauns lietotājs gala
  - Izņemšana (dequeue): O(1) - pirmais rindā saņem grāmatu
- **Bibliotēkas specifika:** Atbilst bibliotēku ētikai un lietotāju cerībām

**Alternatīvie risinājumi:**
- **Steks (LIFO):** Pilnīgi nepiemērots - pēdējais piesakās, pirmais saņem. Nav godīgi
- **Saistītais saraksts:** Varētu darboties, bet bez skaidras FIFO semantikas. Nepieciešama papildus loģika, lai nodrošinātu pareizo kārtību
- **Masīvs:** Neefektīvs - izņemot pirmo elementu, jāpārvieto visi atlikušie elementi. O(n) sarežģītība

**Praktiskais pielietojums:** 
Rinda skaidri komunikē nodomu un nodrošina pareizo uzvedību bez papildus loģikas.

---

### C) Izsniegto grāmatu vēsture

**Izvēlētā datu struktūra:** Cikliskais buferis (Circular Buffer) vai Ierobežots steks

**Pamatojums:**
- **LIFO piekļuve:** Visbiežāk vajag pēdējās grāmatas - steka "top" ir O(1)
- **Fiksēts izmērs:** Tikai 10 grāmatas - vecākā automātiski dzēšas
- **Efektīva atmiņa:** Nemainīgs izmērs, prognozējama atmiņas izmantošana
- **Cache-līdzīga uzvedība:** Jaunākā informācija vienmēr pieejama ātri

**Implementācija:**
```python
class BookHistory:
    def __init__(self, max_size=10):
        self.history = []
        self.max_size = max_size
    
    def add_book(self, book):
        if len(self.history) >= self.max_size:
            self.history.pop(0)  # Dzēš vecāko
        self.history.append(book)  # Pievieno jauno
    
    def get_recent(self):
        return list(reversed(self.history))  # Jaunākās pirmās
```

**Alternatīvie risinājumi:**
- **Saistītais saraksts:** Varētu darboties, bet sarežģītāks. Nepieciešams sekot līdzi izmēram un dzēst no otra gala
- **Rinda (Queue):** Loģiski piemērota vecākā elementa dzēšanai, bet nav optimāla pēdējo elementu piekļuvei
- **Datubāze ar ORDER BY:** Lēnāka, bet laba, ja nepieciešama persistance

---

### D) Jaunu grāmatu pievienošana

**Izvēlētā datu struktūra:** Rinda (Queue - FIFO)

**Pamatojums:**
- **FIFO loģika:** Grāmatas jāapstrādā tādā pašā secībā, kādā saņemtas
- **Darba plūsmas specifika:** Bibliotekārs apstrādā grāmatas pēc kārtas
- **Skaidra semantika:** Rinda skaidri komunikē "to-do" saraksta loģiku
- **Operācijas:**
  - Pievienot jaunu grāmatu: O(1)
  - Apstrādāt nākamo: O(1)

**Alternatīvie risinājumi:**
- **Steks (LIFO):** Nepiemērots - pēdējā saņemtā grāmata tiktu apstrādāta pirmā. Neloģiski
- **Saistītais saraksts:** Funkcionāli varētu darboties, bet nav tik skaidrs kā Queue
- **Masīvs:** Neefektīvs elementu dzēšanai no sākuma (O(n) pārvietošana)

**Praktiskais aspekts:**
Rinda ir dabisks veids, kā modelēt darba uzdevumu plūsmu - ko saņem vispirms, to apstrādā vispirms.

---

## Kopsavilkums

| Situācija | Struktūra | Galvenais iemesls |
|-----------|-----------|-------------------|
| A) Katalogs | Masīvs | O(1) indeksēta piekļuve, fiksēts izmērs |
| B) Rezervācijas | Rinda (FIFO) | Godīgums, FIFO semantika |
| C) Vēsture | Cikliskais buferis/Steks | Piekļuve jaunākajiem, fiksēts izmērs |
| D) Apstrāde | Rinda (FIFO) | Darba plūsma, secīga apstrāde |

## Terminoloģija

- **O(1)** - Konstanta laika sarežģītība (operācija izpildās vienādā laikā neatkarīgi no datu apjoma)
- **O(n)** - Lineāra laika sarežģītība (laiks pieaug proporcionāli datu apjomam)
- **FIFO** - First In, First Out (pirmais iekšā, pirmais ārā)
- **LIFO** - Last In, First Out (pēdējais iekšā, pirmais ārā)
- **Indeksācija** - Piekļuve elementam pēc tā pozīcijas (indeksa) masīvā
- **Enkapsulācija** - Datu paslēpšana objekta iekšienē
- **Persistance** - Datu saglabāšana starp programmas sesijām
