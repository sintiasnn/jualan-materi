<?php

use App\Http\Controllers\MateriController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TransaksiUser;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\DatatablesController;
use Livewire\Volt\Volt;


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
| Session Management Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/sessions', [SessionController::class, 'showActiveSessions'])
        ->name('sessions.index');
    Route::delete('/sessions/{session}', [SessionController::class, 'destroy'])
        ->name('sessions.destroy');
    Route::post('/sessions/force-logout', [SessionController::class, 'forceLogout'])
        ->name('sessions.force-logout');
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'device.limit', 'check.active.session'])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Profile & General Routes
    |--------------------------------------------------------------------------
    */
    Route::view('dashboard', 'dashboard')
        ->middleware('verified')
        ->name('dashboard');

    Route::view('profile', 'livewire.profile.profile')
        ->name('profile');

    Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])
        ->name('profile.updateAvatar');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['verified', 'role:' . User::ROLE_ADMIN])->group(function () {
        // Admin Dashboard
        Route::get('/admin/dashboard', function () {
            return view('livewire.pages.admin.dashboard');
        })->name('admin.dashboard');

        // Admin Users Management
        Route::get('/admin/users', function () {
            return view('livewire.pages.admin.users.index');
        })->name('admin.users');

        Route::get('/admin/users/create', function () {
            return view('livewire.pages.admin.users.create');
        })->name('admin.users.create');

        Route::get('/admin/users/{userId}/edit', function () {
            return view('livewire.pages.admin.users.edit');
        })->name('admin.users.edit');

        Route::get('/admin/users/{user}', function () {
            return view('livewire.pages.admin.users.show');
        })->name('admin.users.show');

        Route::get('/admin/user-groups', function () {
            return view('livewire.pages.admin.users.groups');
        })->name('admin.user-groups');

        // Admin Universitas Management
        Route::get('/admin/universitas', function () {
            return view('livewire.pages.admin.universitas.index');
        })->name('admin.universitas');

        Route::get('/admin/universitas/create', function () {
            return view('livewire.pages.admin.universitas.create');
        })->name('admin.universitas.create');

        Route::get('/admin/universitas/{universitasId}/edit', function () {
            return view('livewire.pages.admin.universitas.edit');
        })->name('admin.universitas.edit');

        Route::get('/admin/universitas/{universitas}', function () {
            return view('livewire.pages.admin.universitas.show');
        })->name('admin.universitas.show');

        // Admin Active Sessions Management
        Route::get('/admin/activesessions', function () {
            return view('livewire.pages.admin.sessions.index');
        })->name('admin.activesessions');
    });

    /*
    |--------------------------------------------------------------------------
    | Tutor Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['verified', 'role:' . User::ROLE_TUTOR])->group(function () {
        Route::get('/tutor/dashboard', function () {
            return view('livewire.pages.tutor.dashboard');
        })->name('tutor.dashboard');
        Route::resource('/tutor/materi', MateriController::class);
        Route::get('tutor/materi/subdomain/{domainCode}', [MateriController::class, 'getSubdomain'])->name('tutor.materi.getSubdomain');

    });

    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['verified', 'role:' . User::ROLE_USER])->group(function () {
        // User Dashboard
        Route::get('/user/dashboard', function () {
            return view('livewire.pages.user.dashboard');
        })->name('user.dashboard');

        // User Purchase
        Route::get('/user/beli', function () {
            return view('livewire.pages.user.beli');
        })->name('user.beli');

        Route::get('/user/beli/checkout/{id}', function ($id) {
            return view('livewire.pages.user.checkout', ['id' => $id]);
        })->name('user.checkout');

        // User Transactions
        Route::get('/user/transaksi', function () {
            return view('livewire.pages.user.daftar-transaksi');
        })->name('user.transaksi');
    });

    /*
    |--------------------------------------------------------------------------
    | Package Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:user'])->group(function () {
        Route::get('/paket', [PaketController::class, 'index']);
        Route::get('/paket/{id}', [PaketController::class, 'show']);
        Route::get('/paket/{id}/ownership', [PaketController::class, 'checkOwnership']);
        Route::post('/paket/{id}/purchase', [PaketController::class, 'purchase']);
    });
});


/*
|--------------------------------------------------------------------------
| DataTables Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin'])->group(function () {
    Route::post('/datatables/active-sessions', [DatatablesController::class, 'activeSessions'])
        ->name('datatables.active-sessions');
    Route::post('/datatables/users', [DatatablesController::class, 'users'])
        ->name('datatables.users');
    Route::post('/datatables/universities', [DatatablesController::class, 'universities'])
        ->name('datatables.universities');
    Route::post('/datatables/universitas', [DatatablesController::class, 'universitas'])
    ->name('datatables.universitas');
});

Route::middleware(['role:user'])->group(function () {
    Route::post('/datatables/transactions', [DatatablesController::class, 'transactions'])
        ->name('datatables.transactions');
});
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::post('/transaction/{id}/pay', [TransactionController::class, 'createPayment'])
    ->middleware(['auth:sanctum', 'check.active.session'])
    ->name('transaction.createPayment');

Route::post('/midtrans/notification', [TransactionController::class, 'notificationHandler']);

require __DIR__.'/auth.php';
