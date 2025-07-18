<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'home'])->name('home');
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
Route::get('/books/{id}', function ($id) {
    $book = App\Models\Book::with('categories')->findOrFail($id);
    return response()->json($book);
});