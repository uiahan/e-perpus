<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function home()
    {
        $books = Book::with('categories')->paginate(12);
        return view('pages.home', compact('books'));
    }

    public function search(Request $request)
    {
        $query = Book::with('categories');

        if ($request->has('q') && $request->q !== '') {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('author', 'like', "%$search%")
                    ->orWhereHas('categories', function ($q2) use ($search) {
                        $q2->where('category_name', 'like', "%$search%");
                    });
            });
        }

        $books = $query->take(15)->get();

        return response()->json($books);
    }
}
