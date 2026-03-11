<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Book;
use App\Models\Category;
use App\Models\BookCopy;
use App\Models\Borrow;

class StatsController extends Controller
{
    public function dashboard()
    {
        $totalBooks = Book::count();
        $totalCopies = BookCopy::count();
        $totalBorrowed = Borrow::where('status', 'active')->count();
        $totalUsers = \App\Models\User::count();

        $mostBorrowedBooks = Book::withCount(['copies as borrow_count' => function ($query) {
            $query->whereHas('borrows', function ($b) {
                $b->where('status', 'active');
            });
        }])->orderBy('borrow_count', 'desc')->take(5)->get();

        $categoriesRepresentation = Category::withCount('books')->get();

        return response()->json([
            'collection_state' => [
                'total_books' => $totalBooks,
                'total_copies' => $totalCopies,
                'currently_borrowed' => $totalBorrowed,
                'available_copies' => $totalCopies - $totalBorrowed
            ],
            'most_viewed_books' => $mostBorrowedBooks,
            'categories_representation' => $categoriesRepresentation,
            'users_count' => $totalUsers
        ]);
    }

    public function degradedBooks()
    {
        $degradedCopies = BookCopy::with('book')
            ->whereIn('condition', ['damaged', 'degraded', 'tached'])
            ->get()
            ->groupBy('book_id');

        $report = $degradedCopies->map(function ($copies, $bookId) {
            $book = $copies->first()->book;
            return [
                'book_id' => $bookId,
                'title' => $book->title,
                'degraded_count' => $copies->count(),
                'copies' => $copies
            ];
        })->values();

        return response()->json($report);
    }
}
