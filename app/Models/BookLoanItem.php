<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BookLoanItem extends Model
{
    protected $guarded = ['id'];

    public function bookLoan()
    {
        return $this->belongsTo(BookLoan::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
