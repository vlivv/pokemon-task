<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannedPokemonController;
use App\Http\Middleware\CheckSecretKey;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(CheckSecretKey::class)->group(function () {
    Route::get('/banned', [BannedPokemonController::class, 'index']);
    Route::post('/banned', [BannedPokemonController::class, 'store']);
    Route::delete('/banned/{banned}', [BannedPokemonController::class, 'destroy']);
});

Route::get('/pokemon/{name}', [BannedPokemonController::class, 'getPokemon']);
 Route::post('/info', [BannedPokemonController::class, 'getPokemons']);
