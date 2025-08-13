<?php

namespace App\Filament\Resources\BookLoanResource\Pages;

use App\Filament\Resources\BookLoanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookLoan extends EditRecord
{
    protected static string $resource = BookLoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->status == 'Pengajuan' && $data['status'] == 'Dalam Masa Pinjaman') {
            $this->record->items()->with('book')->get()->each(function ($item) {
                $item->book->decrement('stock');
            });
        }

        return $data;
    }
}
