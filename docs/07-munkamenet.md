# 07 — Munkamenet

Ez az élő dokumentum. Minden lépés végén Claude Code jelölje ki a checkboxot
és írja be a commit hash-t. Ha új session indul, ebből tudja, hol tart.

**Szabály:** egy lépés = egy plan mode kör = egy commit. Ne fusson előre.

---

## 0. Környezet

- [x] Herd telepítve, PHP 8.4 aktív
- [x] MySQL fut, `fws_task` adatbázis létrehozva
- [x] `laravel new fws-landing` lefutott
- [x] Git repo inicializálva, első commit
- [x] `composer require laravel/boost --dev && php artisan boost:install`
- [x] `.mcp.json` létrejött, Claude Code látja a `laravel-boost` szervert
- [x] Ez a `docs/` mappa és a `CLAUDE.md` bemásolva

Commit: `5fd14bb` + `1a57751` + `5349612`

## 1. Alap konfiguráció

- [x] `.env` beállítva (DB, `APP_NAME`, mail driver)
- [x] `.env.example` szinkronban
- [x] `php artisan storage:link`
- [x] Pint konfigurálva, `composer check` script

Commit: `chore: configure environment, storage link and code style tooling`

## 2. Adatbázis

- [x] Migrációk: `users.is_admin`, `hero_sections`, `projects`, `contact_messages`
- [x] Modellek casts-tel és scope-okkal
- [x] Factory-k mindhárom új modellhez
- [x] Seeder: admin user, hero sor, 4-6 minta referencia
- [x] `php artisan migrate:fresh --seed` hibátlanul lefut

Commit: `9e172d7` — feat: add database schema, models, factories and seeders

## 3. Filament panel + autentikáció

- [x] `filament:install --panels` (már az `5fd14bb` scaffold commitban lefutott)
- [x] `User implements FilamentUser`, `canAccessPanel()` az `is_admin`-ra
- [x] Panel branding a design színeivel (brand név `FÉM`, primary `#6d4bff`)
- [x] Teszt: admin belép, nem-admin 403, vendég a loginra megy

Commit: `26500f5` — feat: install filament panel with admin-only access

## 4. Admin: Referenciák resource

- [x] `Project` resource teljes CRUD-dal
- [x] Borítókép feltöltés `public` diskre
- [x] Sorrendezés, publikált szűrő
- [x] Kép takarítás törléskor és cserekor is
- [x] Teszt: létrehozás menti a rekordot és a fájlt

Commit: `27b1674` — feat: add project resource with cover image upload

## 5. Admin: Hero beállítás oldal

- [x] `ManageHero` custom page
- [x] Mentés + visszajelzés
- [x] Régi kép törlése csere esetén

Commit: `09cad3d` — feat: add hero section settings page

## 6. Frontend: layout és statikus szekciók

- [x] Design tokenek beírva a `docs/04-frontend.md`-be és az `app.css` `@theme`-be
- [x] Betűtípusok self-hostolva (`laravel-vite-plugin` `bunny()`, `latin-ext` subsettel)
- [x] `layout`, `btn`, `header`, `footer` komponensek
- [x] Reszponzív ellenőrzés 375 / 768 / 1440 px-en

Commit: `4ae1efe` — feat: add landing page layout, header and footer

## 7. Frontend: hero és referencia lista

- [x] `LandingController`, `/` route
- [x] `hero` komponens az adatbázisból
- [x] `project-card` és a lista
- [x] Teszt: landing 200, hero szöveg és publikált referenciák látszanak,
      nem publikált nem

Commit: `a6a100b` — feat: render hero and project list from database

## 8. Frontend: kapcsolat modal + aszinkron beküldés

- [x] `contact-modal` komponens (`<dialog>`, fókuszkezelés, ESC)
- [x] `StoreContactMessageRequest` magyar hibaüzenetekkel
- [x] `ContactMessageController::store()` → JSON
- [x] `StoreContactMessage` action
- [x] `fetch()` logika: loading, siker, 422, 429, hálózati hiba
- [x] `throttle:5,1` a route-on
- [x] Tesztek: 201, 422, honeypot, rate limit

Commit: `a153a50` — feat: add async contact form with validation and rate limiting

## 9. E-mail értesítés

- [x] `ContactMessageReceived` queued notification
- [x] Reply-To a beküldőre
- [x] Link az admin nézetre (egyelőre a panel gyökerére, lásd 10. lépés)
- [x] Mailpit-tel kézzel ellenőrizve
- [x] Teszt: `Notification::fake()`, `assertSentTo`

Commit: `e6e2365` — feat: notify admins by email on new contact message

## 10. Admin: üzenetek listája + export

- [x] `ContactMessage` read-only resource
- [x] Olvasatlan badge a menüben, `read_at` állítás megnyitáskor
- [x] Exporter (CSV és XLSX, képlet-injektálás elleni védelemmel)
- [x] A `ContactMessageReceived` admin linkje átállítva a resource view URL-jére
- [x] Kézzel letesztelve futó `queue:work` mellett

Az export job batchekkel és database notification-nel dolgozik, ezért új
migráció a `notifications` és az `exports` tábla, a panelen pedig
`->databaseNotifications()`. A kész export letöltő linkje ebben az
értesítésben érkezik, tehát futó `queue:work` nélkül nem készül el.

A sorok kiemelése helyett állapot badge oszlop jelzi az olvasatlan üzenetet:
a Filament CSS-e előre fordított, egy `recordClasses()`-be írt Tailwind
osztály nem garantáltan lenne benne a bundle-ben.

Commit: `9d7a7ed` — feat: list and export contact messages in admin

## 11. Csiszolás

- [x] Végigkattintás mindkét felületen, mobilon is
- [x] Összevetés a designnal
- [x] `php artisan test` zöld (28 teszt), `vendor/bin/pint --test` tiszta
- [x] Halott kód, kikommentezett rész, boilerplate teszt kitakarítva
- [x] Friss klón teszt: `composer install && npm install && npm run build && php artisan migrate --seed`

A landing 375 / 768 / 1200 / 1500 px-en lett átnézve, az admin desktopon és
375 px-en. Ami a végigkattintáson kiderült:

- A modal validációs hibája addig kint maradt, amíg a látogató javította a
  mezőt. Most `@input`-ra tűnik el az adott mező hibája.
- Az üzenetlista állapot badge-e üresen maradt az olvasatlan sorokon: a
  Filament nem futtatja a formázót `null` state-en. A `state()` most a
  rekordból számol, és teszt is fedi.
- Az `lang/hu` hiánya miatt a keretrendszer validációs üzenetei angolul
  jöttek volna, az export modal két gombja pedig hiányzik a csomag magyar
  fordításából. Mindkettőre van most fordítás.
- A hero elsődleges gombja a designban „Projekt megtekintése" — a seeder
  ezt kapta, és az élő rekord is át lett állítva az admin felületről.
- A `favicon.ico` nulla bájtos volt, helyette `favicon.svg` a landingon és
  a panelen is.

A `docs/04-frontend.md` reszponzív táblázata a Tailwind alapértelmezett
töréspontjaira lett igazítva (a negyedik oszlop 1280px-től, nem 1440-től).

Commit: `5a40be4` + `f14585a` + `5927c94`

## 12. Leadás

- [ ] README megírva (lásd `docs/06-konvenciok.md` README szakasz)
- [ ] Ráfordított idő beírva
- [ ] `.env` nincs a repóban, `git status` tiszta
- [ ] Repo feltöltve / elküldve a `karrier@fws.hu`-ra

Commit: `docs: add readme with setup instructions and time log`

---

## Időnapló

A kiírás kéri a becsült ráfordítást. Vezesd menet közben, ne utólag becsüld meg.

| Dátum | Lépés | Idő |
|---|---|---|
|2026. szeptember 3.  | project setup | 30p |
|2026. szeptember 4. | dokumentumok megírása, átnézése, javítása | 1 óra |
|2026. szeptember 4. | 1. lépés: környezet, Pint, composer check | 30p |
|2026. szeptember 4. | 2. lépés: migrációk, modellek, factory-k, seederek | 45p |
|2026. szeptember 4. | 3. lépés: Filament panel, admin hozzáférés, branding | 30p |
|2026. szeptember 4. | 4. lépés: Referenciák resource, képfeltöltés, sorrendezés | 10p |
|2026. szeptember 4. | 5. lépés: Hero beállítás oldal | 10p |
|2026. szeptember 5. | 6. lépés: design tokenek, betűtípusok, layout, header, footer | 20p |
|2026. szeptember 5. | 7. lépés: landing controller, hero és referencia lista | 10p |
|2026. szeptember 5. | 8. lépés: kapcsolat modal, aszinkron beküldés, rate limit | 20p |
|2026. szeptember 5. | 9. lépés: e-mail értesítés az adminoknak | 10p |
|2026. szeptember 5. | 10. lépés: üzenetek resource, olvasatlan badge, export | 15p |
|2026. szeptember 5. | 11. lépés: csiszolás, végigkattintás, friss klón teszt | 1ó  |
| | | |
| **Összesen** | | |