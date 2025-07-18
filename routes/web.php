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
Route::get('/debug-auth', function () {
    return [
        'auth_web' => Auth::guard('web')->check(),
        'auth_default' => Auth::check(),
        'user' => Auth::user(),
        'session' => session()->all(),
        'cookie' => request()->cookie('laravel_session'),
    ];
});
Route::get('/force-login', function () {
    $user = \App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    return redirect('/debug-auth');
});
