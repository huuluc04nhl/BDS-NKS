<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;

// Homepage Route
Route::get('/', [PropertyController::class, 'home'])->name('home');

// Properties List & Interactive Map Route
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');

// Property Detail Route (by slug)
Route::get('/properties/{slug}', [PropertyController::class, 'show'])->name('properties.show');

// Profile Dashboard Route
Route::get('/profile', function () {
    return view('profile.dashboard');
})->name('profile.dashboard');

// Database API Routing Endpoints
Route::prefix('api')->group(function () {
    Route::post('/register', [PropertyController::class, 'apiRegister']);
    Route::post('/login', [PropertyController::class, 'apiLogin']);
    Route::post('/profile/update', [PropertyController::class, 'apiUpdateProfile']);
    Route::post('/profile/upgrade-host', [PropertyController::class, 'apiUpgradeHost']);
    
    Route::post('/appointments/book', [PropertyController::class, 'apiBookAppointment']);
    Route::get('/appointments/user/{userId}', [PropertyController::class, 'apiGetAppointments']);
    Route::post('/appointments/cancel/{id}', [PropertyController::class, 'apiCancelAppointment']);
    
    Route::post('/favorites/toggle', [PropertyController::class, 'apiToggleFavorite']);
    Route::get('/favorites/user/{userId}', [PropertyController::class, 'apiGetFavorites']);
    
    Route::post('/demands/add', [PropertyController::class, 'apiAddDemand']);
    Route::get('/demands/list', [PropertyController::class, 'apiGetDemands']);
    
    Route::post('/properties/add', [PropertyController::class, 'apiAddProperty']);
    Route::get('/properties/owner/{userId}', [PropertyController::class, 'apiGetOwnerProperties']);
});

// Secure Online Database Migration Trigger for Vercel Serverless
Route::get('/run-migrations-secure-nks', function () {
    if (request()->query('secret') === 'nks_db_migrator_2026') {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            return response()->json([
                'success' => true,
                'connection' => \Illuminate\Support\Facades\DB::getDefaultConnection(),
                'database' => \Illuminate\Support\Facades\DB::connection()->getDatabaseName(),
                'output' => \Illuminate\Support\Facades\Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'connection' => \Illuminate\Support\Facades\DB::getDefaultConnection(),
                'database' => \Illuminate\Support\Facades\DB::connection()->getDatabaseName(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
    abort(403);
})->withoutMiddleware('web');
