<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserSettingsController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user-settings', function (Request $request) {
        return response()->json([
            'dark_mode' => $request->user()->dark_mode ?? false,
            // Otras configuraciones si es necesario
        ]);
    });
});