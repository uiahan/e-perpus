<?php

namespace App\Filament\Resources\BookLoanResource\Pages;

use App\Filament\Resources\BookLoanResource;
use App\Models\BookLoan;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBookLoan extends CreateRecord
{
    protected static string $resource = BookLoanResource::class;
}
