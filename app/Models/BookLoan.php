<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BookLoan extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function items()
    {
        return $this->hasMany(BookLoanItem::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_loan_items')->withTimestamps();
    }
}
