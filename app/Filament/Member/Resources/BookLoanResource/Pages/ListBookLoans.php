<?php

namespace App\Filament\Member\Resources\BookLoanResource\Pages;

use App\Filament\Member\Resources\BookLoanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookLoans extends ListRecords
{
    protected static string $resource = BookLoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
