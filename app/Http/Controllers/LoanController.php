<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * Loan a book to the authenticated user.
     */
    public function loanBook(Request $request): JsonResponse
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $user = Auth::user();
        $book = Book::findOrFail($request->book_id);

        // Check if book is available
        if (!$book->available) {
            return response()->json(['message' => 'Book is not available'], 400);
        }

        // Check if user already has this book loaned
        $existingLoan = Loan::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereNull('return_date')
            ->first();

        if ($existingLoan) {
            return response()->json(['message' => 'You already have this book loaned'], 400);
        }

        // Create loan
        $loan = Loan::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'loan_date' => now(),
            'due_date' => now()->addDays(14), // Default 14 days
        ]);

        // Update book availability
        $book->update(['available' => false]);

        return response()->json([
            'message' => 'Book loaned successfully',
            'loan' => $loan->load('book', 'user')
        ], 201);
    }

    /**
     * Return a loaned book.
     */
    public function returnBook(Request $request): JsonResponse
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
        ]);

        $user = Auth::user();
        $loan = Loan::where('id', $request->loan_id)
            ->where('user_id', $user->id)
            ->whereNull('return_date')
            ->firstOrFail();

        // Update loan with return date
        $loan->update(['return_date' => now()]);

        // Update book availability
        $loan->book->update(['available' => true]);

        return response()->json([
            'message' => 'Book returned successfully',
            'loan' => $loan->load('book', 'user')
        ]);
    }

    /**
     * Get user's current loans.
     */
    public function getUserLoans(): JsonResponse
    {
        $user = Auth::user();
        $loans = Loan::where('user_id', $user->id)
            ->with('book')
            ->get();

        return response()->json($loans);
    }
}
