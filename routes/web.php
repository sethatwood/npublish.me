<?php

use App\Http\Controllers\Nip05Controller;
use Illuminate\Support\Facades\Route;

Route::get('/', [Nip05Controller::class, 'index']);
Route::post('/nip05', [Nip05Controller::class, 'store']);
Route::get('/nip05/manage', [Nip05Controller::class, 'showManageForm']);
Route::post('/nip05/update', [Nip05Controller::class, 'update']);
Route::post('/nip05/delete', [Nip05Controller::class, 'delete']);
Route::get('/.well-known/nostr.json', [Nip05Controller::class, 'serveNostrJson']);
