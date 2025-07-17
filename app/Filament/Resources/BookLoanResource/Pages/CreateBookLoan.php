<?php

namespace App\Filament\Resources\BookLoanResource\Pages;

use App\Filament\Resources\BookLoanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookLoan extends CreateRecord
{
    protected static string $resource = BookLoanResource::class;
}
