# FÉM Stúdió — landing oldal és admin felület

FWS próbafeladat. Egy egyszerű landing oldal (hero, referencia lista,
kapcsolatfelvételi modal) és a hozzá tartozó admin felület, amiről a hero
szekció, a referenciák és a beérkezett üzenetek kezelhetők.

**Stack:** PHP 8.4 · Laravel 13 · Filament 5 · Livewire 4 · TailwindCSS 4 (Vite) ·
Alpine.js · MySQL 8 · Pest 5 · Laravel Pint

A fejlesztés menete, az adatmodell és a döntések részletesen a [`docs/`](docs/)
mappában vannak dokumentálva.

---

## Követelmények

| | |
|---|---|
| PHP | 8.4 (a fejlesztés `8.4.23`-on ment; a `composer.json` minimuma `^8.3`) |
| MySQL | 8+ (vagy MariaDB 10.10+) |
| Node | 20+ (a fejlesztés `v20.20.1`-en ment) |
| Composer | 2 |

## Telepítés

```bash
composer install

cp .env.example .env
php artisan key:generate
```

Az `.env`-ben állítsd be az adatbázis elérést (alapértelmezés:
`fws_task` adatbázis, `root` felhasználó jelszó nélkül):

```dotenv
DB_DATABASE=fws_task
DB_USERNAME=root
DB_PASSWORD=
```

Ezután:

```bash
php artisan migrate --seed
php artisan storage:link

npm install
npm run build

php artisan serve
```

A landing a `/`, az admin a `/admin` útvonalon érhető el.

Fejlesztés közben `npm run build` helyett `npm run dev` (vagy `composer dev`,
ami a szervert, a queue workert és a Vite-ot együtt indítja).

### Seed képek

A seederek a `database/seeders/assets/` mappából másolják a képeket a `public`
diskre (`hero/hero.*` és `projects/*.jpg`). Ha a mappák üresek, a `db:seed`
figyelmeztetést ír, de lefut — a hero kép nélkül, a referenciák placeholder
útvonallal jönnek létre. Részletek a mappa saját
[README-jében](database/seeders/assets/README.md).

## Admin belépés

A `AdminUserSeeder` egy admin felhasználót hoz létre:

| | |
|---|---|
| URL | `/admin` |
| E-mail | `admin@example.test` |
| Jelszó | `password` |

A panelre csak `is_admin = true` felhasználó léphet be, nem-admin `403`-at kap
(`User::canAccessPanel()`).

## Queue

`QUEUE_CONNECTION=database`, tehát a háttérmunkákhoz **futnia kell egy workernek**:

```bash
php artisan queue:work
```

Két dolog múlik rajta:

- **E-mail értesítés** — a `ContactMessageReceived` notification `ShouldQueue`,
  worker nélkül nem megy ki levél az adminoknak.
- **Export** — a Filament exportja batchekkel dolgozik, és a kész fájl letöltő
  linkje database notification-ben (a panel harang ikonja alatt) érkezik meg.
  Worker nélkül az export elindul, de soha nem készül el.

A `composer dev` ezt is elindítja (`queue:listen`), ha azzal futtatod a projektet.

## E-mail lokálisan

Az `.env.example` [Mailpit](https://github.com/axllent/mailpit)-re van állítva
(`MAIL_HOST=127.0.0.1`, `MAIL_PORT=1025`), a levelek a `http://localhost:8025`
felületen nézhetők meg. Ha nincs Mailpit, a `MAIL_MAILER=log` is elég — a levél
a `storage/logs/laravel.log`-ba kerül.

## Ráfordított idő

**Kb. 6 óra**, nem mértem konkrétan, viszont minden rész után beírtam, hogy nagyjából mennyi lehetett.

Lépésenkénti bontásban a [`docs/07-munkamenet.md`](docs/07-munkamenet.md)
időnaplója vezeti.

---

## Fontosabb döntések

**A kapcsolat űrlap `fetch()`-csel megy, nem Livewire-rel.**
A landing oldal így nem húzza be a Livewire runtime-ot egyetlen háromnevű
űrlap kedvéért — a Livewire csak az admin panel miatt van jelen. A modal natív
`<dialog>` elem Alpine-nal, a beküldés kezeli a loading, siker, `422`, `429` és
hálózati hiba állapotot. Védelem: `throttle:5,1` a route-on és egy `prohibited`
szabályú `website` honeypot mező.

**A hero egysoros tábla, nem kulcs-érték settings store.**
A `hero_sections` tábla típusos oszlopokat kap (cím, alcím, két CTA, kép), így
a Filament form 1:1 leképezhető rá, és nincs szükség serializálásra. A
`HeroSection::current()` üres táblán is ad vissza instance-t, ezért sem a
frontend, sem az admin oldal nem kényszerül null-vizsgálatra.

**A képtakarítás a model esemény, nem a Filament page dolga.**
A `HeroSection` és a `Project` `booted()` metódusa törli az árván maradó fájlt
a `public` diskről csere és törlés esetén is. Így ugyanaz fut adminból,
seederből és tesztből — nem lehet megkerülni egy másik belépési ponton.

**Vékony controller, külön Action.**
A validáció `StoreContactMessageRequest`-ben (magyar hibaüzenetekkel), az
üzleti logika a `StoreContactMessage` action-ben, a controller csak összeköti
őket és JSON-t ad vissza.

**Az üzenetek resource read-only.**
Az üzenet látogatói adat: az admin listázza, olvassa és exportálja, de nem
szerkeszti. A megnyitás állítja a `read_at` mezőt, az olvasatlanok száma
badge-ként látszik a menüben.

**Az exporter védve van képlet-injektálás ellen.**
A szöveges cellák elé aposztróf kerül, hogy egy `=`-vel kezdődő üzenet ne
képletként fusson le a táblázatkezelőben.

**Olvasatlan üzenet: állapot badge oszlop, nem sorkiemelés.**
A Filament CSS-e előre fordított, egy `recordClasses()`-be írt Tailwind osztály
nem garantáltan kerülne bele a bundle-be — ezért látható oszlop jelzi az
állapotot.

**Nulla extra composer csomag.**
A `require` blokkban a Laravel, a Filament és a Tinker van, semmi más. A feladat
a saját struktúrát hivatott bemutatni, itt a kevesebb függőség előny.

## Projektstruktúra

```
app/
  Actions/          StoreContactMessage — üzleti logika a controlleren kívül
  Filament/
    Exports/        ContactMessageExporter (CSV, XLSX)
    Pages/          ManageHero — hero beállítás oldal
    Resources/      Projects (teljes CRUD), ContactMessages (read-only)
  Http/
    Controllers/    LandingController, ContactMessageController
    Requests/       StoreContactMessageRequest
  Models/           HeroSection, Project, ContactMessage, User
  Notifications/    ContactMessageReceived (queued)
resources/views/
  components/       layout, header, hero, project-card, contact-modal, footer, btn
  landing.blade.php
docs/               feladatkiírás, architektúra, adatmodell, konvenciók, munkamenet
```

## Tesztelés és kódstílus

```bash
php artisan test --compact     # 28 teszt
vendor/bin/pint --test         # kódstílus ellenőrzés
composer check                 # a kettő egyben
```

A tesztek a valódi logikát fedik: landing renderelés (publikált és nem
publikált referencia), kapcsolat űrlap (`201`, `422`, honeypot, rate limit,
e-mail kiküldés), admin hozzáférés, referencia létrehozás képfeltöltéssel,
hero mentés és az üzenetlista olvasatlan állapota.
