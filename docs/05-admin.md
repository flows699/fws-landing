# 05 — Admin felület (Filament 5)

> **Emlékeztető:** minden Filament kód előtt `laravel-boost` → `search-docs`.
> A Filament 4/5 API érdemben eltér a v3-tól (schema-alapú formok, átszervezett
> Resource fájlstruktúra, Livewire 4). Fejből írt v3 kód itt nem fordul le.

## Panel

`php artisan filament:install --panels` hozza létre az
`app/Providers/Filament/AdminPanelProvider.php`-t. Alap útvonal: `/admin`.

Beállítandó:
- `->login()` — alapértelmezett Filament autentikáció (a kiírás ezt kéri)
- brand név és szín a designból
- magyar navigációs feliratok (`->navigationLabel()` / `navigationGroup`)

A `User` modelen:

```php
class User extends Authenticatable implements FilamentUser
{
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }
}
```

Enélkül **minden** regisztrált user belépne a panelbe, ezért a `canAccessPanel()`
implementálása kötelező.

## Resource: Referenciák (`Project`)

Teljes CRUD. Generálás: `php artisan make:filament-resource Project --generate`,
majd a generált kódot **át kell nézni és tisztítani** — a generátor kimenete
kiindulópont, nem végeredmény.

**Form:**
- `title` — required, max 255
- `slug` — title-ből generálva, szerkeszthető, unique validációval
- `cover_path` — `FileUpload`, `public` disk, `projects` könyvtár, képre korlátozva,
  méretkorlát (2 MB), image editor a arányvágáshoz
- `published_at` — `DatePicker`, required, `Y.m.d` megjelenítés
- `is_published` — toggle
- `sort_order` — nincs a formban, a táblázat reorder kezeli

Leírás és külső link mező **nincs**: a design a kártyán csak borítóképet, dátumot és
címet mutat, ezért a séma sem tartalmaz ilyen oszlopot (lásd `docs/03-adatmodell.md`).

**Fontos:** a `FileUpload` alapból bármilyen fájltípust elfogad, ezért az `image()`
hívás kötelező. A `FILESYSTEM_DISK` `local`, tehát a `disk('public')` mindenhol
(feltöltés és `ImageColumn` is) explicit legyen.

**Table:**
- borítókép thumbnail oszlop
- cím (kereshető, rendezhető)
- megjelenés dátuma (rendezhető, `Y.m.d`)
- publikált (boolean ikon, `TernaryFilter`-rel szűrhető)
- `reorderable('sort_order')` a sorrendezéshez, alapból `sort_order` szerint rendezve

**Fontos:** a borítókép fájlt takarítsuk el, ha a referenciát törlik **vagy** ha a
képet lecserélik. Mindkettő a modell event-jein megy: `deleting` törli az aktuális
fájlt, `updating` a lecserélt régit (`isDirty('cover_path')` vizsgálattal, hogy az
újraseedelés ne törölje ki a saját képét).

## Resource: Beérkezett üzenetek (`ContactMessage`)

**Read-only resource.** Üzenetet nem hozunk létre és nem szerkesztünk az adminban —
csak listázunk, megnézünk, exportálunk, esetleg törlünk.

Ezért: `canCreate(): false`, `canEdit(): false`. Nézet: `ViewAction` vagy
infolist a részletekhez.

**Table:**
- név, e-mail, üzenet kezdete (`limit(50)`), beérkezés ideje
- olvasatlan sorok kiemelése (`read_at` null)
- szűrő: olvasatlan / összes, dátumtartomány
- `ViewAction` megnyitáskor állítsa `read_at`-et

**Badge a menüben:** `getNavigationBadge()` adja vissza az olvasatlanok számát,
így az olvasatlan üzenetek száma a navigációban azonnal látszik.

## Export

A Filament panel builder **beépített exportert** hoz — nem kell külső csomag.

```bash
php artisan make:filament-exporter ContactMessage
```

Majd a táblához `ExportBulkAction` és/vagy fejléc `ExportAction`.

Amit tudni kell:
- az export **queue-n fut**, tehát kell futó worker: `php artisan queue:work`
- a `filament:install` létrehozza az `exports` / `imports` táblákat — ellenőrizd,
  hogy a migrációk lefutottak
- alapból CSV és XLSX is jön; XLSX-hez `openspout/openspout` kell (`composer require openspout/openspout`)
- a kész exportról a Filament belső értesítést küld a felhasználónak

A README-ben írd le, hogy exporthoz futnia kell a queue workernek: futó worker
nélkül az export nem készül el.

Ha az XLSX körüli csomagfüggőség bonyodalmat okoz, a CSV önmagában is
kielégíti a kiírást — a feladat "legalább egy" formátumot kér.

## E-mail értesítés új üzenetről

**Ne** a Filament resource-ban legyen, hanem a beküldési útvonalon —
az üzenet a fronton keletkezik.

Lánc: `ContactMessageController::store()` → `StoreContactMessage` action →
`Notification::send($admins, new ContactMessageReceived($message))`

- a Notification implementálja a `ShouldQueue`-t
- `$admins = User::where('is_admin', true)->get()`
- a mail tartalmazza: feladó nevét, e-mailjét, üzenetét, és egy linket
  az admin nézetre (`ContactMessageResource::getUrl('view', ['record' => $message])`)
- `Reply-To` legyen a beküldő e-mail címe, így a válasz közvetlenül
  a levelezőprogramból elküldhető

**Lokális teszteléshez:** `MAIL_MAILER=smtp` + Mailpit (`brew install mailpit`,
`mailpit`, SMTP a `localhost:1025`, webes UI `localhost:8025`). Herd Pro esetén
a beépített mail inspector is elviszi. Legvégső esetben `MAIL_MAILER=log`
és a `storage/logs/laravel.log`-ban nézed meg.

Teszteléskor `Notification::fake()` és `assertSentTo`.

## Hero beállítás oldal

Nem resource — egyetlen rekord szerkesztése. Custom Filament Page:

```bash
php artisan make:filament-page ManageHero
```

- `Filament\Pages\Page`-ből származik, form schema-val
- `mount()`-ban betölti a `HeroSection::current()` adatait a form state-be
- `save()` metódus menti vissza
- navigációs csoport: "Beállítások"
- mezők: `title`, `subtitle`, `cta_label`, `cta_url`, `image_path` (FileUpload)
- mentés után `Notification::make()->success()` visszajelzés

Ha a régi hero képet lecserélik, a régi fájl törlődjön.

## Tesztek az adminhoz

Ne teszteld végig a Filament UI-t. Elég 3-4 fókuszált teszt:

- admin user beléphet `/admin`-ra, nem-admin 403-at kap
- `Project` létrehozása menti a rekordot és a fájlt (`Storage::fake('public')`)
- hero mentés után az új szöveg megjelenik a landing oldalon
- export action lefut hiba nélkül

Livewire komponens teszteléshez: `livewire()->test(...)` helper.