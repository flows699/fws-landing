# 04 — Frontend / Landing oldal

Design: `fws - Laravel próbafeladat - FÉM`, INDEX frame (`1:2`), 1920px széles.
Az alábbi értékek a Figma fájlból származnak, nem becslések.

**Megjegyzés:** a designban **nincsenek Figma variable-ök** definiálva, minden
érték nyers. A tokenizálás alább a mi döntésünk, nem a designeré.

**Megjegyzés:** a design **csak 1920px desktopot tartalmaz.** A töréspontok és
a mobil layout saját levezetés — lásd a "Reszponzivitás" szakaszt.

## Design tokenek

Tailwind 4 CSS-first konfiguráció, `resources/css/app.css`:

```css
@import "tailwindcss";

@theme {
  /* Színek */
  --color-ink: #16161a;          /* fő szöveg, sötét gomb, footer háttér */
  --color-accent: #6d4bff;       /* logó pont, egyetlen kiemelőszín */
  --color-body: #46464d;         /* navigáció, másodlagos szöveg */
  --color-muted: #7c7c84;        /* kártya dátum */
  --color-line: #e4e4e0;         /* világos elválasztó */
  --color-surface: #ececea;      /* kártya kép placeholder háttér */

  /* Footer (sötét felület) */
  --color-footer-label: #75757e;
  --color-footer-text: #c2c2c8;
  --color-footer-muted: #8d8d96;
  --color-footer-strong: #cfcfd4;

  /* Tipográfia */
  --font-display: "Space Grotesk", sans-serif;
  --font-sans: "IBM Plex Sans", sans-serif;
  --font-mono: "IBM Plex Mono", monospace;

  /* Layout */
  --spacing-container: 1536px;
  --spacing-section: 104px;
}
```

Nem tokenizált, inline maradhat: `rgba(255,255,255,0.86)` (fejléc háttér),
`rgba(255,255,255,0.78)` (hero bekezdés), `rgba(255,255,255,0.4)` (hero
másodlagos gomb kerete), `rgba(255,255,255,0.12)` (footer elválasztó).

### Betűtípusok

Három család, mind Google Fonts / SIL OFL:

| Család | Súlyok | Hol |
|---|---|---|
| Space Grotesk | Bold 700, Medium 500 | logó, H1, H2, kártyacímek |
| IBM Plex Sans | Regular 400 | folyó szöveg, footer szövegek |
| IBM Plex Mono | Regular 400, Medium 500 | navigáció, gombfeliratok, dátumok, footer címkék |

Töltsd le és bundle-özd őket (`@fontsource/*` npm csomagok vagy manuális woff2),
ne CDN-ről hivatkozz — a `docs/06-konvenciok.md` tiltja a CDN script/link tageket,
és a saját hosting gyorsabb is.

### Tipográfiai skála (1920px desktop)

| Elem | Család / súly | Méret | Sorköz | Betűköz | Szín |
|---|---|---|---|---|---|
| Hero H1 | Space Grotesk Bold | 66px | 67.32px | -1.32px | fehér |
| Szekciócím H2 | Space Grotesk Bold | 44px | 44.88px | -0.88px | `ink` |
| Kártyacím H3 | Space Grotesk Medium | 18.5px | 22.94px | -0.185px | `ink` |
| Hero bevezető | IBM Plex Sans | 18.5px | 29.6px | 0 | `rgba(255,255,255,.78)` |
| Footer szöveg | IBM Plex Sans | 14.5px | 23.2px | 0 | `footer-text` |
| Navigációs link | IBM Plex Mono | 12px | 19.2px | +1.2px | `body` |
| Gombfelirat | IBM Plex Mono | 12px | 19.2px | +0.96px | kontextusfüggő |
| Kártya dátum | IBM Plex Mono | 11px | 17.6px | +0.66px | `muted` |
| Footer oszlopcímke | IBM Plex Mono Medium | 11px | 17.6px | +1.54px | `footer-label` |
| Footer copyright | IBM Plex Mono | 11.5px | 18.4px | +1.61px | `footer-label` |
| Logó (fejléc) | Space Grotesk Bold | 21px | 33.6px | -0.21px | `ink` + `accent` pont |
| Logó (footer) | Space Grotesk Bold | 24px | 38.4px | -0.24px | fehér + `accent` pont |

A negatív betűköz a Space Grotesk címeknél és a pozitív a Mono elemeknél
a design karakterének a lényege. Elhagyásukkal a tipográfia jellegtelenné
válik, ezért a táblázat értékeitől ne térj el.

### Border radius

A design sehol nem használ lekerekítést: sem a gombokon, sem a kártyákon,
sem a képeken. Ez tudatos ipari karakter. Ne tegyél `rounded`-ot semmire,
a modalra se.

## Layout

- Frame: 1920px
- Konténer: 1536px, középre zárva → 192px margó kétoldalt
- Konténeren belül 40px padding → tényleges tartalomszélesség 1456px

Tailwindben: `mx-auto max-w-[1536px] px-10`

| Szekció | Magasság / térköz |
|---|---|
| Fejléc | 74px + 1px alsó keret |
| Hero | 780px |
| Munkáink szekció | 104px felső és alsó padding |
| Footer | 80px felső padding |

## Fejléc

- Háttér: `rgba(255,255,255,0.86)` + `backdrop-filter: blur(5px)`
- Alsó keret: 1px `--color-line`
- Logó balra 40px-nél: „FÉM" `ink` színnel, utána egy pont `accent` színnel
- Navigáció középen, 34px gap: **Munkáink, Stúdió, Folyamat**
- Jobbra a Kapcsolat gomb

A blur + áttetszőség miatt a fejléc `sticky top-0 z-50` — a designból ez nem
derül ki egyértelműen, de a backdrop-blur csak görgetés közben ragadó fejlécnél
értelmes. Vedd fel a README döntések közé.

## Gombok

Mind 43px magas, szögletes, 1px kerettel, Mono 12px felirattal, ~18-19px
vízszintes paddinggel.

| Variáns | Háttér | Keret | Szöveg | Hol |
|---|---|---|---|---|
| Sötét | `ink` | `ink` | fehér | fejléc „Kapcsolat" |
| Világos | fehér | fehér | `ink` | hero „Projekt megtekintése" |
| Kontúr (sötét háttéren) | átlátszó | `rgba(255,255,255,.4)` | fehér | hero „A stúdióról" |

Egy Blade komponens (`<x-btn variant="dark">`), három variánssal.

## Hero

- Teljes szélességű háttérkép, `object-cover`, 780px magas
- Fölötte sötét scrim, balról erősebb
- Tartalom a konténerben balra zárva, 780px széles blokk
- H1 (max ~803px szélességen tör), bevezető bekezdés 528px szélességen
- Két gomb egymás mellett, 14px gap

**Javítás:** a világos gomb felirata a designban „Projekt megtekintése",
nem „Kezdjük a tervezést". A kontúros gombé „A stúdióról".

A scrim a Figmában raszterként van exportálva, tehát a pontos gradiens stopok
nem olvashatók ki. Közelítés, a rendert nézve:

```css
background: linear-gradient(90deg,
  rgba(22,22,26,.78) 0%,
  rgba(22,22,26,.38) 55%,
  rgba(22,22,26,.15) 100%);
```

Ezt szemre kell hangolni a screenshot ellen — ez az egyetlen érték
a dokumentumban, ami nem pontos.

**Megjegyzés:** a hero frame neve `heroSlides`, a belső frame `1 / 3` — a designer
háromdiás carouselre gondolt. **A kiírás viszont egyetlen, adminból szerkeszthető
hero szekciót kér.** Egy hero rekordot csinálunk, carousel nélkül. Ez a döntés
kerüljön be a README döntések szakaszába.

## Munkáink szekció

- Szekciócím „Munkáink", alatta 1px `--color-line` elválasztó
- 4 oszlopos rács, kártyaszélesség 361.5px, 30px gap
- Kártya felépítése fentről lefelé:
  1. **Kép** — 361.5 × 271.125px, azaz pontosan **4:3**, `object-cover`,
     háttér `--color-surface` amíg tölt
  2. **Dátum** — 18px-el a kép alatt, Mono 11px, `YYYY.MM.DD` formátum
  3. **Cím** — 29px-el a dátum alatt, Space Grotesk Medium 18.5px

**A kártyán csak ez a három adat van.** Nincs leírás, nincs ügyfélnév,
nincs „tovább" link. A kiírás azt kéri, hogy „a design alapján elvárt adatok"
jelenjenek meg — tehát ennél többet ne vegyél fel.

## Footer

- Háttér `--color-ink`
- Felső rész három oszlopban:
  - **Brand**: logó 24px, alatta „Ipari formatervező stúdió Budapesten."
  - **Menü**: Mono címke, alatta 4 link 38.6px sorközzel
  - **Kapcsolat**: Mono címke, alatta cím / e-mail / telefon
- Alsó keret `rgba(255,255,255,.12)`
- Alsó sáv: copyright balra, „Adatvédelem" és „Impresszum" jobbra

A footer tartalma a kiírás szerint **statikus**, nem kell adminból szerkeszteni.

### Footer szövegek

A Figmából kiolvasva (footer node `1:106`):

| Hol | Szöveg |
|---|---|
| Brand tagline | Ipari formatervező stúdió Budapesten. |
| „Menü" oszlop | Munkáink · Stúdió · Folyamat · Kapcsolat |
| „Kapcsolat" oszlop | 1061 Budapest Fém utca 99. · studio@fem.hu · +36 1 234 5678 |
| Alsó sáv balra | © 2026 FÉM Stúdió — Minden jog fenntartva |
| Alsó sáv jobbra | Adatvédelem · Impresszum |

A brand oszlop alatti `foot-social` frame a designban üresen renderelődik,
ezért nem építjük meg.

Színek: a tagline `footer-text`, a menülinkek és a cím `footer-strong`,
az oszlopcímkék és a copyright `footer-label`, a jogi linkek `footer-muted`.

## Kapcsolat modal

A designban nincs megrajzolva — a kiírás annyit mond, hogy „a kapott designba illő".
Tehát a fenti tokenekből kell felépíteni:

- Fehér panel, **szögletes sarok**, 1px `--color-line` keret
- Backdrop: `rgba(22,22,26,.6)`
- Cím: Space Grotesk Bold, a H2-nél kisebb (28-32px)
- Mezőcímkék: Mono 11px, +1.54px betűköz, `--color-muted` — ugyanaz a stílus,
  mint a footer oszlopcímkék
- Input: szögletes, 1px `--color-line` keret, fókuszban `--color-accent` keret,
  IBM Plex Sans 14.5px
- Küldés gomb: sötét variáns
- Hibaüzenet: Mono 11px, piros — a designban nincs hibaszín, használj
  visszafogottat (pl. `#c0392b`), és vedd fel a `@theme`-be

Viselkedés:
- natív `<dialog>` elem (fókuszcsapda, ESC, backdrop ingyen)
- backdrop kattintás zár
- nyitáskor az első input fókuszt kap
- nyitva a háttér nem scrollozik
- `aria-labelledby` a modal címére mutat

## Beküldés folyamata

`resources/js/app.js`:

1. `submit` esemény, `preventDefault()`
2. gomb `disabled`, felirat „Küldés…"
3. `fetch('/contact', { method:'POST', headers:{ 'X-CSRF-TOKEN':…, 'Accept':'application/json', 'Content-Type':'application/json' }, body: JSON.stringify(data) })`
4. válasz:
   - `201` → az űrlap helyén sikerüzenet, gomb „Bezárás"
   - `422` → `errors` objektumból mezőnkénti hibaszöveg
   - `429` → „Túl sok próbálkozás, várj egy percet."
   - egyéb / hálózati hiba → általános hibaüzenet, gomb visszaáll
5. a gomb minden ágon visszaáll `finally`-ben

## Route-ok

```php
Route::get('/', LandingController::class)->name('landing');
Route::post('/contact', [ContactMessageController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
```

## Validáció (`StoreContactMessageRequest`)

| mező | szabály |
|---|---|
| `name` | `required, string, max:120` |
| `email` | `required, email:rfc, max:255` |
| `message` | `required, string, min:10, max:5000` |

Hibaüzenetek magyarul, a FormRequest `messages()` metódusában.
Honeypot mező (`website`, rejtett, `nullable, prohibited`) olcsó spam-védelem.

## Blade szerkezet

```blade
<x-layout>
    <x-header />
    <x-hero :hero="$hero" />
    <x-projects :projects="$projects" />
    <x-footer />
    <x-contact-modal />
</x-layout>
```

`LandingController::__invoke()` adja át: `$hero = HeroSection::current()`,
`$projects = Project::published()->get()`. Semmi query a Blade-ben.

## Reszponzivitás

A design csak desktopot ad, tehát ez saját levezetés. Töréspontok:

| Breakpoint | Konténer | Kártyarács | H1 | H2 |
|---|---|---|---|---|
| < 640px | px-5 | 1 oszlop | 36px | 28px |
| 640–1023px | px-8 | 2 oszlop | 44px | 34px |
| 1024–1439px | px-10 | 3 oszlop | 54px | 38px |
| ≥ 1440px | max-w-[1536px] px-10 | 4 oszlop | 66px | 44px |

A negatív betűközt kisebb méreteknél arányosan csökkentsd (`-0.02em` konstans
jobban skálázódik, mint a fix px érték).

Mobilon a navigáció összecsukódik. A kiírás szerint a menüelemek statikusak,
tehát elég egy egyszerű hamburger, ami lenyit egy listát — ne építs
elaborált off-canvas menüt.

Hero mobilon: 780px helyett `min-h-[520px]`, a scrim gradiens fentről lefelé
menjen (`180deg`) balról jobbra helyett, különben olvashatatlan a szöveg.

Ellenőrzés: 375px, 768px, 1024px, 1440px, 1920px.

## Képek

- `php artisan storage:link` kötelező
- `Storage::url($project->cover_path)` a Blade-ben
- kártyaképek fix 4:3 arányban, `object-cover`
- `loading="lazy"` mindenre a hero kivételével
- explicit `width`/`height` vagy `aspect-[4/3]`, hogy ne ugráljon a layout

## Vite

`npm run dev` fejlesztéskor, `npm run build` leadás előtt.
`public/build` nincs commitolva.

## Amit ne csinálj

- Ne tegyél `rounded`-ot semmire.
- Ne húzz be CSS keretrendszert Tailwind mellé.
- Ne tegyél CDN `<script>`/`<link>` taget a layoutba.
- Ne csinálj a modalból Livewire komponenst — lásd `docs/02-architektura.md`.
- Ne építsd meg a hero carouselt. Egy hero rekord van.
- Ne animálj a designban nem látható módon. Hover állapotokat tehetsz
  a gombokra és kártyákra, de visszafogottan (opacity vagy háttérszín váltás,
  nem elmozdulás).