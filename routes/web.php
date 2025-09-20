<?php

use Adithyan\ActivityLogVisualizer\Http\Controllers\ActivityLogController;

Route::prefix('activity-log-visualizer')
    ->name('activitylog-visualizer.')
    // ->middleware(['web']) // Add any additional middleware you need
    ->group(function () {

        // Main dashboard
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');

        // Show single activity details
        Route::get('/activity/{id}', [ActivityLogController::class, 'show'])->name('show');

        // Export functionality
        Route::get('/export', [ActivityLogController::class, 'export'])->name('export')->middleware('throttle:3,1');

        // Admin features (add appropriate middleware)
        Route::middleware(['auth', 'admin'])->group(function () {
            // Bulk delete
            Route::delete('/bulk-delete', [ActivityLogController::class, 'bulkDelete'])->name('bulk-delete');

            // Clear old activities
            Route::delete('/clear-old', [ActivityLogController::class, 'clearOld'])->name('clear-old');
        });
    });