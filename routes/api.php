<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::name('api.')
    ->group(function () {
        Route::prefix('/courses')
            ->name('courses.')
            ->controller(CourseController::class)
            ->group(function () {
                Route::get('/bests', 'bests')->name('bests');
            });
    });
