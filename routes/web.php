<?php
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('home');
    Route::get('/search', [IndexController::class, 'search'])->name('search');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // User login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Admin login routes
    Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User borrow routes
    Route::resource('borrows', BorrowController::class)->names([
        'index' => 'borrows.index',
        'create' => 'borrows.create',
        'store' => 'borrows.store',
        'show' => 'borrows.show',
        'edit' => 'borrows.edit',
        'update' => 'borrows.update',
        'destroy' => 'borrows.destroy',
    ]);
    Route::post('/borrows/{borrow}/return', [BorrowController::class, 'return'])->name('borrows.return');
    Route::post('/borrows/{borrow}/request-return', [BorrowController::class, 'requestReturn'])->name('borrows.request-return');
});

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Books management routes
    Route::resource('books', BooksController::class)->names([
        'index' => 'admin.books.index',
        'create' => 'admin.books.create',
        'store' => 'admin.books.store',
        'show' => 'admin.books.show',
        'edit' => 'admin.books.edit',
        'update' => 'admin.books.update',
        'destroy' => 'admin.books.destroy',
    ]);
    Route::post('/books/{book}/add-copies', [BooksController::class, 'addCopies'])->name('admin.books.add-copies');
    Route::post('/books/{book}/remove-copies', [BooksController::class, 'removeCopies'])->name('admin.books.remove-copies');

    // Borrows management routes
    Route::resource('borrows', BorrowController::class)->names([
        'index' => 'admin.borrows.index',
        'create' => 'admin.borrows.create',
        'store' => 'admin.borrows.store',
        'show' => 'admin.borrows.show',
        'edit' => 'admin.borrows.edit',
        'update' => 'admin.borrows.update',
        'destroy' => 'admin.borrows.destroy',
    ]);
    Route::post('/borrows/{borrow}/return', [BorrowController::class, 'return'])->name('admin.borrows.return');
    Route::post('/borrows/{borrow}/approve', [BorrowController::class, 'approve'])->name('admin.borrows.approve');
    Route::post('/borrows/{borrow}/reject', [BorrowController::class, 'reject'])->name('admin.borrows.reject');
    Route::post('/borrows/{borrow}/approve-return', [BorrowController::class, 'approveReturn'])->name('admin.borrows.approve-return');

    // Users management routes
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});
