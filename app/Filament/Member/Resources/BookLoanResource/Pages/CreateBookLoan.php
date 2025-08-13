<?php

namespace App\Filament\Member\Resources\BookLoanResource\Pages;

use App\Filament\Member\Resources\BookLoanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookLoan extends CreateRecord
{
    protected static string $resource = BookLoanResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['member_id'] = auth()->user()->member->id;
        $data['due_date'] = now();
        $data['status'] = 'Pengajuan';
        return $data;
    }

}
