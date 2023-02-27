<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;

Route::name('api.')
    ->group(function () {
        Route::prefix('/courses')
            ->name('courses.')
            ->controller(CourseController::class)
            ->group(function () {
                Route::get('/bests', 'bests')->name('bests');
            });

        Route::prefix('/feedback')
            ->name('feedback.')
            ->controller(FeedbackController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
            });
    });
