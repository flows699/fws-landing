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

- [ ] `Project` resource teljes CRUD-dal
- [ ] Borítókép feltöltés `public` diskre
- [ ] Sorrendezés, publikált szűrő
- [ ] Kép takarítás törléskor
- [ ] Teszt: létrehozás menti a rekordot és a fájlt

Commit: `feat: add project resource with cover image upload`

## 5. Admin: Hero beállítás oldal

- [ ] `ManageHero` custom page
- [ ] Mentés + visszajelzés
- [ ] Régi kép törlése csere esetén

Commit: `feat: add hero section settings page`

## 6. Frontend: layout és statikus szekciók

- [ ] Design tokenek beírva a `docs/04-frontend.md`-be és az `app.css` `@theme`-be
- [ ] `layout`, `header`, `footer` komponensek
- [ ] Reszponzív ellenőrzés 375 / 768 / 1440 px-en

Commit: `feat: add landing page layout, header and footer`

## 7. Frontend: hero és referencia lista

- [ ] `LandingController`, `/` route
- [ ] `hero` komponens az adatbázisból
- [ ] `project-card` és a lista
- [ ] Teszt: landing 200, hero szöveg és publikált referenciák látszanak,
      nem publikált nem

Commit: `feat: render hero and project list from database`

## 8. Frontend: kapcsolat modal + aszinkron beküldés

- [ ] `contact-modal` komponens (`<dialog>`, fókuszkezelés, ESC)
- [ ] `StoreContactMessageRequest` magyar hibaüzenetekkel
- [ ] `ContactMessageController::store()` → JSON
- [ ] `StoreContactMessage` action
- [ ] `fetch()` logika: loading, siker, 422, 429, hálózati hiba
- [ ] `throttle:5,1` a route-on
- [ ] Tesztek: 201, 422, rate limit

Commit: `feat: add async contact form with validation and rate limiting`

## 9. E-mail értesítés

- [ ] `ContactMessageReceived` queued notification
- [ ] Reply-To a beküldőre
- [ ] Link az admin nézetre
- [ ] Mailpit-tel kézzel ellenőrizve
- [ ] Teszt: `Notification::fake()`, `assertSentTo`

Commit: `feat: notify admins by email on new contact message`

## 10. Admin: üzenetek listája + export

- [ ] `ContactMessage` read-only resource
- [ ] Olvasatlan badge a menüben, `read_at` állítás megnyitáskor
- [ ] Exporter (CSV, ha megy XLSX is)
- [ ] Kézzel letesztelve futó `queue:work` mellett

Commit: `feat: list and export contact messages in admin`

## 11. Csiszolás

- [ ] Végigkattintás mindkét felületen, mobilon is
- [ ] Összevetés a designnal
- [ ] `php artisan test` zöld, `vendor/bin/pint --test` tiszta
- [ ] Halott kód, kikommentezett rész, `dd()` maradék kitakarítva
- [ ] Friss klón teszt: `composer install && npm install && npm run build && php artisan migrate --seed`

Commit: `refactor: clean up and align landing page with design`

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
| | | |
| **Összesen** | | |