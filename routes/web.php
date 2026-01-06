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

Route::get('/cause-list', [App\Http\Controllers\CauseListController::class, 'index'])->name('cause-list');

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
    Route::post('/cases/{case}/invite-lawyer', [CaseController::class, 'inviteLawyer'])->name('cases.invite-lawyer');
    Route::post('/cases/{case}/accept-lawyer', [CaseController::class, 'acceptLawyer'])->name('cases.accept-lawyer');
    Route::post('/cases/{case}/submit', [CaseController::class, 'submit'])->name('cases.submit');
    Route::post('/cases/{case}/hearings', [CaseController::class, 'storeHearing'])->name('cases.hearings.store');
    Route::post('/cases/{case}/approve', [CaseController::class, 'approve'])->name('cases.approve');
    Route::post('/cases/{case}/reject', [CaseController::class, 'reject'])->name('cases.reject');
    Route::post('/cases/{case}/assign-judge', [CaseController::class, 'assignJudge'])->name('cases.assign-judge');
    Route::post('/cases/{case}/orders', [App\Http\Controllers\CaseOrderController::class, 'store'])->name('cases.orders.store');
    Route::get('/cases/{case}/orders/{order}/download', [App\Http\Controllers\CaseOrderController::class, 'download'])->name('cases.orders.download');
});

require __DIR__.'/auth.php';
