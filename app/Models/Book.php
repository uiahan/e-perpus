<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function book_loans() {
        return $this->hasMany(BookLoan::class);
    } 
}
