# 06 — Konvenciók

## PHP

- `declare(strict_types=1);` minden fájl tetején
- Minden metóduson paraméter- és return type
- Konstruktor property promotion
- `final` az Action és Notification osztályokon (nem tervezünk öröklést)
- Nincs `env()` hívás a `config/` mappán kívül — mindig `config('...')`

## Elnevezés

| Mit | Hogyan | Példa |
|---|---|---|
| Model | egyes szám, PascalCase | `ContactMessage` |
| Tábla | többes szám, snake_case | `contact_messages` |
| Controller | egyes szám + `Controller` | `ContactMessageController` |
| Action | ige + tárgy | `StoreContactMessage` |
| Route név | pontozott | `contact.store` |
| Blade komponens | kebab-case | `contact-modal` |
| Teszt | leíró mondat | `it('stores a contact message')` |

## Nyelvhasználat

- **Kód, változónevek, commit üzenetek: angol.**
- **Felhasználónak látszó szöveg (Blade, validációs üzenet, Filament label): magyar.**

Ne keverd. A kevert nyelvű elnevezés következetlenné teszi a kódbázist,
és megnehezíti a keresést.

## Kommentek

Kevés, és csak arra, ami nem derül ki a kódból: **miért**, nem **mit**.

```php
// Rossz:
// Elmentjük az üzenetet
$message->save();

// Jó:
// Reply-To a beküldő címére megy, hogy az admin a levelezőből tudjon válaszolni.
```

PHPDoc csak ott, ahol a típusrendszer nem elég (pl. tömb elemtípusa).

## Tesztelés (Pest)

`tests/Feature/` a hangsúly, unit teszt csak izolált logikára.

Kötelező lefedettség:

| Teszt | Mit ellenőriz |
|---|---|
| landing oldal | 200, hero szöveg és publikált referenciák megjelennek |
| landing oldal | nem publikált referencia **nem** jelenik meg |
| contact store | érvényes adat → 201, rekord az adatbázisban |
| contact store | érvénytelen adat → 422, mezőnkénti hibák |
| contact store | értesítés kimegy az adminoknak (`Notification::fake()`) |
| contact store | rate limit életbe lép a 6. kérésnél |
| admin hozzáférés | admin be, nem-admin 403 |
| project CRUD | létrehozás menti a rekordot és a képet (`Storage::fake`) |
| hero mentés | az új szöveg megjelenik a landing oldalon |

Futtatás: `php artisan test`. Adatbázis: `RefreshDatabase`, külön
teszt-adatbázis vagy SQLite in-memory a `phpunit.xml`-ben.

## Formázás

`vendor/bin/pint` — Laravel preset, konfiguráció nélkül.
Commit előtt: `vendor/bin/pint --dirty`.

Opcionálisan `composer.json`-ba:
```json
"scripts": {
    "check": ["vendor/bin/pint --test", "@php artisan test"]
}
```

## Git

Conventional commits:

```
feat: add contact message modal with async submission
fix: prevent hero image orphan on replace
test: cover contact form validation errors
refactor: extract StoreContactMessage action from controller
chore: configure pint and add composer check script
docs: add setup instructions and time log to README
```

- Ne legyen "wip", "asd", "final2" commit.
- Ne legyen egyetlen óriási "initial commit", amiben benne az egész app —
  a git history mutassa a fejlesztés lépéseit.
- `main` ágon dolgozz, feature branch ekkora feladatnál felesleges.

## README (leadás előtt kötelező)

Tartalma:
1. Mi ez, egy bekezdés
2. Követelmények (PHP 8.4, MySQL 8, Node 20+)
3. Telepítés lépésről lépésre, másolható parancsokkal
4. Admin belépési adatok (seedelt user)
5. Megjegyzés: exporthoz kell futó `queue:work`
6. Megjegyzés: e-mail lokálisan Mailpit / log driver
7. **Ráfordított idő** — a kiírás kifejezetten kéri
8. Rövid szakasz a fontosabb döntésekről (miért fetch és nem Livewire,
   miért egysoros hero model)