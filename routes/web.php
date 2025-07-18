<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookController::class, 'home'])->name('home');
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
Route::get('/books/{id}', function ($id) {
    $book = App\Models\Book::with('categories')->findOrFail($id);
    return response()->json($book);
});
Route::get('/force-login', function () {
    $user = \App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    return redirect('/debug-auth');
});

Route::get('/debug-auth', function () {
    dd(Auth::user(), auth()->check(), session()->all());
});
Route::get('/test-session', function () {
    session(['foo' => 'bar']);
    return 'Session set';
});

Route::get('/get-session', function () {
    return session('foo');
});

