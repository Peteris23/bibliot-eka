<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth routes
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/register', [AuthController::class, 'apiRegister']);

// Book routes
Route::middleware('web')->group(function () {
    Route::get('books', [BookController::class, 'index']);
    Route::post('books', [BookController::class, 'store']);
    Route::get('books/search', [BookController::class, 'search']);
    Route::get('books/{isbn}', [BookController::class, 'show']);
    Route::put('books/{isbn}', [BookController::class, 'update']);
    Route::delete('books/{isbn}', [BookController::class, 'destroy']);
});

// Loan routes (protected)
Route::middleware('web')->group(function () {
    Route::post('loans/loan', [LoanController::class, 'loanBook']);
    Route::post('loans/return', [LoanController::class, 'returnBook']);
    Route::get('loans/my-loans', [LoanController::class, 'getUserLoans']);
});
