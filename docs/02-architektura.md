# 02 — Architektúra

## Alapelv

A feladat kicsi, tehát **nem** kell DDD, repository pattern, service layer minden réteggel.
A cél az, hogy minden osztály a Laravel konvenciói szerinti helyre kerüljön,
és a struktúra végig következetes maradjon.

Két szabály:
1. A controller **nem** tartalmaz üzleti logikát.
2. Ha egy művelet több lépésből áll, önálló Action osztályba kerül.

## Mappaszerkezet

```
app/
├── Actions/
│   └── StoreContactMessage.php        # üzenet mentés + notification diszpécselés
├── Filament/
│   ├── Pages/
│   │   └── ManageHero.php             # hero beállítás oldal (custom page)
│   └── Resources/
│       ├── ContactMessages/           # csak listázás + megtekintés + export
│       └── Projects/                  # teljes CRUD
├── Http/
│   ├── Controllers/
│   │   ├── LandingController.php      # __invoke, a landing oldal
│   │   └── ContactMessageController.php # store, JSON választ ad
│   └── Requests/
│       └── StoreContactMessageRequest.php
├── Models/
│   ├── ContactMessage.php
│   ├── HeroSection.php
│   ├── Project.php
│   └── User.php
├── Notifications/
│   └── ContactMessageReceived.php     # queued, admin usereknek
└── Providers/
    └── Filament/AdminPanelProvider.php

resources/
├── css/app.css                        # Tailwind 4 belépési pont
├── js/app.js                          # Alpine + a contact modal logika
└── views/
    ├── components/
    │   ├── layout.blade.php
    │   ├── btn.blade.php
    │   ├── header.blade.php
    │   ├── hero.blade.php
    │   ├── contact-modal.blade.php
    │   ├── project-card.blade.php
    │   └── footer.blade.php
    └── landing.blade.php

routes/web.php
database/migrations/
database/seeders/
tests/Feature/
docs/
```

## Rétegek és felelősségek

| Réteg | Felelősség | Nem csinál |
|---|---|---|
| Route | URL → controller leképezés, névvel | logika |
| FormRequest | validáció, jogosultság | mentés |
| Controller | request → Action → response | validáció, query, mail |
| Action | egy üzleti művelet, tranzakcióval ha kell | HTTP-tudás |
| Model | attribútumok, casts, scope-ok, relációk | üzleti folyamat |
| Notification | e-mail tartalom és csatorna | döntés, hogy kimenjen-e |
| Filament Resource | admin CRUD leírás | frontend logika |

## Miért nem Livewire a frontend kapcsolatűrlap?

A Filament magával hozza a Livewire 4-et, tehát elérhető lenne. Mégis
sima `fetch()` + Alpine a választás, mert:

- a kiírás "aszinkron beküldést" kér, nem reaktív komponenst,
- így a landing oldal statikus Blade marad, gyors és cache-elhető,
- a controller + FormRequest + Action lánc jobban megmutatja a Laravel struktúrát,
  ami a feladat kimondott célja.

Ezt a döntést a README-ben is érdemes egy mondatban leírni.

## Hero szekció: miért egysoros model és nem key-value settings tábla?

Egy `hero_sections` tábla, mindig egyetlen sorral (`HeroSection::current()`).
Előnye a key-value settings táblával szemben: típusos oszlopok, migrációban látszik
a séma, a Filament form közvetlenül a modelre köthető, nincs extra csomag.
Hátránya: új mező = új migráció. Ezen a méreten ez elfogadható tradeoff.