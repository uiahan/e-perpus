<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function home()
    {
        $books = Book::with('categories')->paginate(12);
        $categories = Category::all();
        return view('pages.home', compact('books', 'categories'));
    }

    public function show($id)
    {
        $book = Book::with('categories')->findOrFail($id);
        return response()->json($book);
    }

    public function search(Request $request)
    {
        $query = Book::with('categories');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('author', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('categories', function ($q2) use ($search) {
                        $q2->where('category_name', 'like', "%$search%");
                    });
            });
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('id', $request->category_id);
            });
        }

        $books = $query->take(20)->get();

        return response()->json($books);
    }
}
