<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    } 

    public function book_loans() {
        return $this->hasMany(BookLoan::class);
    }
}
