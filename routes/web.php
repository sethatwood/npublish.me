<?php

use App\Http\Controllers\Nip05Controller;

Route::get('/', [Nip05Controller::class, 'showForm'])->name('nip05.form');
Route::post('/', [Nip05Controller::class, 'submitForm'])->name('nip05.submit');

Route::get('/.well-known/nostr.json', [Nip05Controller::class, 'nip05Endpoint'])->name('nip05.endpoint');
