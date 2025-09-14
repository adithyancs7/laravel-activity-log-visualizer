<?php

use Http\Controllers\ActivityLogController;

Route::middleware(['web', 'auth']) // remove auth if you want public while developing
    ->prefix('activitylog-visualizer')
    ->name('activitylog.visualizer.')
    ->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
    });