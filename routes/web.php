<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\TransaksiUser;

//Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;

        return match ($role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_TUTOR => redirect()->route('tutor.dashboard'),
            User::ROLE_USER => redirect()->route('user.dashboard'),
            // default => redirect()->route('dashboard'),
        };
    }
    return view('welcome'); // Show home page if not authenticated
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'livewire.profile.profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('livewire.pages.admin.dashboard');
    })->name('admin.dashboard')->middleware('role:' . User::ROLE_ADMIN);

    Route::get('/tutor/dashboard', function () {
        return view('livewire.pages.tutor.dashboard');
    })->name('tutor.dashboard')->middleware('role:' . User::ROLE_TUTOR);

    Route::get('/user/dashboard', function () {
        return view('livewire.pages.user.dashboard');
    })->name('user.dashboard')->middleware('role:' . User::ROLE_USER);
   
    Route::get('/user/beli', function () {
        return view('livewire.pages.user.beli');
    })->name('user.beli')->middleware('role:' . User::ROLE_USER);

    Route::get('/user/transaksi', function () {
        return view('livewire.pages.user.daftar-transaksi');
    })->name('user.transaksi')->middleware('role:' . User::ROLE_USER);

    Route::get('/user/beli/checkout/{id}', function ($id) {
        return view('livewire.pages.user.checkout', ['id' => $id]);
    })->name('user.checkout')->middleware('role:' . User::ROLE_USER); 

});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/paket', [PaketController::class, 'index']); // List packages
    Route::get('/paket/{id}', [PaketController::class, 'show']); // Package details
    Route::get('/paket/{id}/ownership', [PaketController::class, 'checkOwnership']); // Ownership check
    Route::post('/paket/{id}/purchase', [PaketController::class, 'purchase']); // Package purchase
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/transaction/{id}/pay', [TransactionController::class, 'createPayment']);
});

Route::post('/midtrans/notification', [TransactionController::class, 'notificationHandler']);



require __DIR__.'/auth.php';
