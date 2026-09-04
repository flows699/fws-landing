# 03 — Adatmodell

Négy tábla. A `users` a Laravel default, csak kiegészítjük.

## `users`

Laravel 12 alapértelmezett migráció, változatlanul. Kiegészítés:

| oszlop | típus | megjegyzés |
|---|---|---|
| `is_admin` | `boolean` default `false` | ki kap e-mail értesítést és ki léphet a panelbe |

A `User` modelen implementálni kell a `Filament\Models\Contracts\FilamentUser`
interfészt, `canAccessPanel()` → `$this->is_admin`.

Seeder: egy admin user, `admin@example.test` / `password`, `is_admin = true`.
A README-ben szerepeljenek ezek a belépési adatok.

## `hero_sections`

Egyetlen sort tartalmaz. A frontend `HeroSection::current()`-tel olvassa,
ami az első sort adja vissza (vagy egy üres példányt, ha nincs).

| oszlop | típus | null | megjegyzés |
|---|---|---|---|
| `id` | `id` | – | |
| `title` | `string` | nem | fő cím |
| `subtitle` | `text` | igen | alcím / leírás |
| `cta_primary_label` | `string` | igen | világos gomb, designban „Kezdjük a tervezést" |
| `cta_primary_url` | `string` | igen | |
| `cta_secondary_label` | `string` | igen | kontúr gomb, designban „A stúdióról" |
| `cta_secondary_url` | `string` | igen | |
| `image_path` | `string` | igen | háttérkép, `storage/app/public/hero/` alatt |
| timestamps | | | |

Seeder: egy sor, a designból vett szöveggel és egy placeholder képpel.

## `projects` (referenciák)

| oszlop | típus | null | megjegyzés |
|---|---|---|---|
| `id` | `id` | – | |
| `title` | `string` | nem | |
| `slug` | `string` unique | nem | title-ből generált |
| `cover_path` | `string` | nem | borítókép, 4:3, `storage/app/public/projects/` |
| `published_at` | `date` | nem | a kártyán `YYYY.MM.DD` formátumban jelenik meg |
| `is_published` | `boolean` default `true` | nem | |
| `sort_order` | `unsignedInteger` default `0` | nem | admin felületen húzható sorrend |
| timestamps | | | |

Scope: `scopePublished()` → `where('is_published', true)->orderBy('sort_order')`.

A mezőlista a designhoz ellenőrizve. A kártya **három** adatot mutat:
borítókép (4:3), dátum, cím. Ügyfélnév, leírás és külső link **nincs** a designban,
ezért nem is vesszük fel — a kiírás azt kéri, hogy „a design alapján elvárt adatok"
jelenjenek meg. Olyan mezőt, amit a design nem jelenít meg, nem veszünk fel.

## `contact_messages`

| oszlop | típus | null | megjegyzés |
|---|---|---|---|
| `id` | `id` | – | |
| `name` | `string` | nem | |
| `email` | `string` | nem | |
| `message` | `text` | nem | |
| `ip_address` | `string(45)` | igen | spam-visszakövetéshez |
| `read_at` | `timestamp` | igen | admin megjelölheti olvasottként |
| timestamps | | | |

Index: `created_at` (a lista alapértelmezett rendezése).

## Kapcsolatok

Nincs reláció a táblák között. Ez szándékos: a feladat egyet sem indokol,
kitalált kapcsolatot pedig nem vezetünk be.

## Migráció szabályok

- Egy migráció = egy tábla.
- `down()` mindig legyen kitöltve.
- Ha a séma menet közben változik: **új** migráció, ne írd át a régit.
- Futtatás: `php artisan migrate`. Reset fejlesztés közben: `php artisan migrate:fresh --seed`.

## Factory-k

Mindhárom modelhez legyen factory — a feature tesztek ezekre épülnek.
A `ProjectFactory` `cover_path`-hoz használjon `UploadedFile::fake()`-et
vagy fix placeholder útvonalat, ne töltsön le képet a netről.