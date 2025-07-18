<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function home() {
        $books = Book::with('categories')->paginate(12);
        return view('pages.home', compact('books'));
    }
}
