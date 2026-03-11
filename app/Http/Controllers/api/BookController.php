<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Book;
use App\Models\BookCopy;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->has('new_arrivals')) {
            $query->orderBy('created_at', 'desc');
        }

        if ($request->has('popular')) {
            // Logic for popular books (most borrowed)
            // For now, let's just return all, will refine if needed
            $query->withCount('copies')
                  ->orderBy('copies_count', 'desc');
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'total_copies' => 'integer|min:1',
            'published_at' => 'nullable|date',
            'cover_image' => 'nullable|string'
        ]);

        $book = Book::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'total_copies' => $request->total_copies ?? 1,
            'available_copies' => $request->total_copies ?? 1,
            'published_at' => $request->published_at,
            'cover_image' => $request->cover_image
        ]);

        // Create copies
        for ($i = 0; $i < ($request->total_copies ?? 1); $i++) {
            BookCopy::create([
                'book_id' => $book->id,
                'condition' => 'good',
                'status' => 'available'
            ]);
        }

        return response()->json($book->load('copies'), 201);
    }

    public function show(string $id)
    {
        $book = Book::with(['category', 'copies'])->findOrFail($id);
        return response()->json($book);
    }

    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);
        
        $request->validate([
            'category_id' => 'exists:categories,id',
            'title' => 'string|max:255',
            'author' => 'string|max:255',
            'isbn' => 'string|unique:books,isbn,'.$book->id,
            'total_copies' => 'integer|min:0',
            'published_at' => 'nullable|date',
            'cover_image' => 'nullable|string'
        ]);

        $book->update($request->all());

        return response()->json($book);
    }

    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }

    public function search(Request $request)
    {
        $query = Book::with('category');

        if ($request->has('q')) {
            $q = $request->q;
            $query->where(function($b) use ($q) {
                $b->where('title', 'LIKE', "%$q%")
                  ->orWhere('author', 'LIKE', "%$q%")
                  ->orWhereHas('category', function($c) use ($q) {
                      $c->where('name', 'LIKE', "%$q%");
                  });
            });
        }

        return response()->json($query->get());
    }

    public function updateCopy(Request $request, $bookId, $copyId)
    {
        $request->validate([
            'condition' => 'string|in:good,damaged,degraded',
            'status' => 'string|in:available,borrowed,maintenance'
        ]);

        $copy = BookCopy::where('book_id', $bookId)->findOrFail($copyId);
        $copy->update($request->only(['condition', 'status']));

        return response()->json($copy);
    }
}
