<?php

use App\Http\Controllers\CaseController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

use App\Models\CourtCase;

Route::middleware([
    'auth:sanctum',
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('Serestadar')) {
            $pendingCases = CourtCase::with(['filedBy'])
                ->whereHas('caseStatus', fn($q) => $q->where('slug', 'pending-approval'))
                ->get();
            return Inertia::render('Dashboards/SerestadarDashboard', [
                'pendingCases' => $pendingCases
            ]);
        }
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Case Management Routes
    Route::resource('cases', CaseController::class);
    Route::get('/cases/{case}/download-arji', [App\Http\Controllers\ReportController::class, 'downloadArji'])->name('cases.download-arji');
});

require __DIR__.'/auth.php';
