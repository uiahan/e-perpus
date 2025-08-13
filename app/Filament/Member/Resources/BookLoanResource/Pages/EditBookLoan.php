<?php

namespace App\Filament\Member\Resources\BookLoanResource\Pages;

use App\Filament\Member\Resources\BookLoanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookLoan extends EditRecord
{
    protected static string $resource = BookLoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
