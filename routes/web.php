<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LanguageController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/search', function () {
    return view('search');
})->name('search');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/library', function () {
    return view('library');
})->middleware('auth')->name('library');

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

Route::get('/contacts', function () {
    return view('contacts');
})->middleware('auth')->name('contacts');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes - Admin only
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
});

// Public routes
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');

// Language switcher
Route::post('/language', [LanguageController::class, 'switch'])->name('language.switch');
