<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HierarchyController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/hierarchy/{user}', [HierarchyController::class, 'show']);
    Route::get('/hierarchy/{user}/children', [HierarchyController::class, 'children']);
    Route::get('/hierarchy/{user}/upline', [HierarchyController::class, 'upline']);
    Route::get('/hierarchy/{user}/metrics', [HierarchyController::class, 'metrics']);
});
