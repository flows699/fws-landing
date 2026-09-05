<?php

declare(strict_types=1);

/*
 * Csak azok a szabályok, amelyeket az alkalmazás ténylegesen használhat:
 * a kapcsolatfelvételi űrlap, a referencia űrlap és a hero beállítás oldal.
 * A fel nem sorolt kulcsokra a Laravel az angol fallback fordítást adja.
 */

return [

    'accepted' => 'A(z) :attribute elfogadása kötelező.',
    'after' => 'A(z) :attribute legyen későbbi, mint :date.',
    'before' => 'A(z) :attribute legyen korábbi, mint :date.',
    'boolean' => 'A(z) :attribute csak igaz vagy hamis lehet.',
    'date' => 'A(z) :attribute nem érvényes dátum.',
    'date_format' => 'A(z) :attribute nem felel meg a(z) :format formátumnak.',
    'dimensions' => 'A(z) :attribute képméretei nem megfelelőek.',
    'email' => 'A(z) :attribute nem érvényes e-mail cím.',
    'file' => 'A(z) :attribute legyen fájl.',
    'image' => 'A(z) :attribute legyen kép.',
    'integer' => 'A(z) :attribute legyen egész szám.',
    'max' => [
        'array' => 'A(z) :attribute legfeljebb :max elemet tartalmazhat.',
        'file' => 'A(z) :attribute legfeljebb :max kilobájt lehet.',
        'numeric' => 'A(z) :attribute legfeljebb :max lehet.',
        'string' => 'A(z) :attribute legfeljebb :max karakter lehet.',
    ],
    'mimes' => 'A(z) :attribute a következő típusok egyike legyen: :values.',
    'mimetypes' => 'A(z) :attribute a következő típusok egyike legyen: :values.',
    'min' => [
        'array' => 'A(z) :attribute legalább :min elemet tartalmazzon.',
        'file' => 'A(z) :attribute legalább :min kilobájt legyen.',
        'numeric' => 'A(z) :attribute legalább :min legyen.',
        'string' => 'A(z) :attribute legalább :min karakter legyen.',
    ],
    'numeric' => 'A(z) :attribute legyen szám.',
    'prohibited' => 'A(z) :attribute kitöltése nem engedélyezett.',
    'required' => 'A(z) :attribute megadása kötelező.',
    'string' => 'A(z) :attribute legyen szöveg.',
    'unique' => 'Ez a(z) :attribute már foglalt.',
    'url' => 'A(z) :attribute nem érvényes URL.',

    'custom' => [],

    'attributes' => [],

];
