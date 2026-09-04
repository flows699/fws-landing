# Seed assets

A seederek innen másolják a képeket a `storage/app/public` diskre.

- `hero/hero.jpg` — hero háttérkép (bármilyen kiterjesztés jó: jpg, png, webp)
- `projects/*.jpg` — referencia borítóképek, 4:3 arányban, névsor szerint
  rendezve (`01-...`, `02-...`), a fájlok sorrendje adja a `sort_order` értéket

Ha a mappák üresek, a `db:seed` figyelmeztetést ír, de lefut: a hero kép nélkül,
a referenciák pedig placeholder útvonallal jönnek létre.
