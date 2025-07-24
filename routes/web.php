<?php

use App\Exports\ReportExport;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', [BookController::class, 'home'])->name('home');
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
Route::get('/books/{id}', function ($id) {
    $book = App\Models\Book::with('categories')->findOrFail($id);
    return response()->json($book);
});

// Route::middleware('auth')->group(function () { 
// });

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function (){
    Route::get('/login', 'showLogin')->name('show.login');
    Route::get('/register', 'showRegister')->name('show.register');
    Route::post('/store', 'register')->name('register');
    Route::post('/login/post', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::group(['prefix' => 'history', 'controller' => HistoryController::class], function (){
    Route::get('/h', 'history')->name('show.history');
});

Route::get('/export-laporan', function () {
    $from = request('from');
    $to = request('to');

    return Excel::download(new ReportExport($from, $to), 'laporan_peminjaman.xlsx');
})->name('export.excel');
