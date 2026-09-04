# 01 — Feladatkiírás és elfogadási kritériumok

## Eredeti kiírás (változatlanul)

Készíts egy egyszerű landing oldalt a csatolt design alapján és mellé egy admin felületet.

Az oldal célja nem egy komplex alkalmazás elkészítése, hanem annak bemutatása, hogy hogyan
dolgozol Laravel környezetben, hogyan strukturálod a kódot, és mennyire tudsz tiszta,
átlátható megoldást készíteni.

### Elvárt technológiák

- PHP 8.4+ | Laravel 12+
- Filament 5
- TailwindCSS
- MySQL 8+ vagy MariaDB 10.10+

### Design

Nem elvárás a pixel-perfect megvalósítás, azonban törekedj az igényes, a tervhez hű kivitelezésre.

### Frontend / Landing oldal

- Fejléc
  - Kapcsolat gomb:
    - A kapott designba illő felugró ablak (modal)
    - A felugró ablak megjelenít egy kapcsolatfelvételre alkalmas űrlapot (név, e-mail cím, üzenet)
    - Űrlap tartalma beküldése aszinkron módon (oldal betöltés nélkül)
- Banner / hero szekció
  - Szöveg és kép módosítható admin felületről
- Munkáink / Referencia lista
  - Cím és borítókép feltölthető és a design alapján elvárt adatok megjelennek.
- Footer

A gombok és menüelemek statikusak, az oldal azon részei dinamikusak, amelyek kifejezetten
ki lettek emelve, hogy elvárt funkcionalitás.

### Backend / Admin

- Alapértelmezett autentikáció
- Kapcsolatfelvételi űrlap által beküldött üzenetek fogadása, listázása
  - E-mail értesítés küldése az adminisztrátoroknak üzenet beérkezéséről
  - Export funkció legalább egy szöveges/adattáblás fájlformátumban (CSV, XLSX, PDF, DOCX, XML, JSON)
- Referenciák kezelésére (létrehozás, listázás, szerkesztés, törlés) alkalmas felület
- Hero szekció tartalmának módosítására szolgáló beállítás oldal

### Leadás

Git repository, karrier@fws.hu vagy a próbafeladat oldal. Határidő: 3 nap.
**Rögzíteni kell, körülbelül mennyi időt vett igénybe a feladat.**

---

## Elfogadási kritériumok (checklist)

A feladat akkor kész, ha minden pont igaz:

### Frontend

- [ ] `/` route betölti a landing oldalt, minden szekcióval (header, hero, referenciák, footer)
- [ ] A hero cím, szöveg és kép az adatbázisból jön, nem hardcode-olt
- [ ] A referencia lista az adatbázisból jön, borítóképpel
- [ ] A "Kapcsolat" gomb modalt nyit, ami a design stílusához illik
- [ ] A modal űrlapja: név, e-mail, üzenet
- [ ] Beküldés `fetch()`-csel, oldal újratöltés nélkül
- [ ] Sikeres beküldés után visszajelzés a modalban
- [ ] Validációs hiba esetén mezőnkénti hibaüzenet, oldal újratöltés nélkül
- [ ] Az oldal reszponzív (mobil, tablet, desktop)

### Admin

- [ ] `/admin` login oldal, Filament alapértelmezett autentikációval
- [ ] Beérkezett üzenetek listája, olvasható részletnézettel
- [ ] Új üzenet érkezésekor e-mail megy az adminoknak (queue-n)
- [ ] Üzenetek exportálhatók (CSV vagy XLSX)
- [ ] Referenciák: létrehozás, listázás, szerkesztés, törlés
- [ ] Referencia borítókép feltöltés működik, a kép megjelenik a landing oldalon
- [ ] Hero beállítás oldal: szöveg és kép módosítható, a változás azonnal látszik a fronton

### Minőség

- [ ] `php artisan test` zöld
- [ ] `vendor/bin/pint --test` hibátlan
- [ ] `README.md` tartalmaz: telepítési lépéseket, `.env.example`-t, becsült ráfordított időt
- [ ] `.env.example` kitöltött, `.env` nincs commitolva
- [ ] Git history olvasható, értelmes commitokkal
- [ ] Friss klónon `composer install && npm install && php artisan migrate --seed && npm run build`
      után az app működik