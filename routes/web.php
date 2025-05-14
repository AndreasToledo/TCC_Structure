<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EssayController;

Route::get('/', function () {
    return view('corrigir');
});

Route::post('/corrigir-redacao', [EssayController::class, 'resultado']);