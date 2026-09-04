<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// A 7. lépésben a LandingController váltja ezt a closure-t.
Route::get('/', fn () => view('landing'))->name('landing');
