<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TransaksiUser;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;

        return match ($role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_TUTOR => redirect()->route('tutor.dashboard'),
            User::ROLE_USER => redirect()->route('user.dashboard'),
        };
    }
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Profile & General Routes
|--------------------------------------------------------------------------
*/
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'livewire.profile.profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])
    ->middleware(['auth'])
    ->name('profile.updateAvatar');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
// Admin Dashboard
Route::get('/admin/dashboard', function () {
    return view('livewire.pages.admin.dashboard');
})->name('admin.dashboard')->middleware(['auth', 'verified', 'role:' . User::ROLE_ADMIN]);

// Admin Users Management
Route::get('/admin/users', function () {
    return view('livewire.pages.admin.users.index');
})->name('admin.users')->middleware(['auth', 'verified', 'role:' . User::ROLE_ADMIN]);

Route::get('/admin/users/create', function () {
    return view('livewire.pages.admin.users.create');
})->name('admin.users.create')->middleware(['auth', 'verified', 'role:' . User::ROLE_ADMIN]);

Route::get('/admin/users/{userId}/edit', function () {
    return view('livewire.pages.admin.users.edit');
})->name('admin.users.edit')->middleware(['auth', 'verified', 'role:' . User::ROLE_ADMIN]);

Route::get('/admin/users/{user}', function () {
    return view('livewire.pages.admin.users.show');
})->name('admin.users.show')->middleware(['auth', 'verified', 'role:' . User::ROLE_ADMIN]);

Route::get('/admin/user-groups', function () {
    return view('livewire.pages.admin.users.groups');
})->name('admin.user-groups')->middleware(['auth', 'verified', 'role:' . User::ROLE_ADMIN]);

/*
|--------------------------------------------------------------------------
| Tutor Routes
|--------------------------------------------------------------------------
*/
Route::get('/tutor/dashboard', function () {
    return view('livewire.pages.tutor.dashboard');
})->name('tutor.dashboard')->middleware(['auth', 'verified', 'role:' . User::ROLE_TUTOR]);

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
// User Dashboard
Route::get('/user/dashboard', function () {
    return view('livewire.pages.user.dashboard');
})->name('user.dashboard')->middleware(['auth', 'verified', 'role:' . User::ROLE_USER]);

// User Purchase
Route::get('/user/beli', function () {
    return view('livewire.pages.user.beli');
})->name('user.beli')->middleware(['auth', 'verified', 'role:' . User::ROLE_USER]);

Route::get('/user/beli/checkout/{id}', function ($id) {
    return view('livewire.pages.user.checkout', ['id' => $id]);
})->name('user.checkout')->middleware(['auth', 'verified', 'role:' . User::ROLE_USER]);

// User Transactions
Route::get('/user/transaksi', function () {
    return view('livewire.pages.user.daftar-transaksi');
})->name('user.transaksi')->middleware(['auth', 'verified', 'role:' . User::ROLE_USER]);

/*
|--------------------------------------------------------------------------
| Package Routes
|--------------------------------------------------------------------------
*/
Route::get('/paket', [PaketController::class, 'index'])
    ->middleware(['auth', 'role:user']);

Route::get('/paket/{id}', [PaketController::class, 'show'])
    ->middleware(['auth', 'role:user']);

Route::get('/paket/{id}/ownership', [PaketController::class, 'checkOwnership'])
    ->middleware(['auth', 'role:user']);

Route::post('/paket/{id}/purchase', [PaketController::class, 'purchase'])
    ->middleware(['auth', 'role:user']);

/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
*/
Route::post('/transaction/{id}/pay', [TransactionController::class, 'createPayment'])
    ->middleware(['auth:sanctum'])
    ->name('transaction.createPayment');

Route::post('/midtrans/notification', [TransactionController::class, 'notificationHandler']);

require __DIR__.'/auth.php';