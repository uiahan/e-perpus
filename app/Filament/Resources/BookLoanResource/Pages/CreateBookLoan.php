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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $year = now()->year;

        $lastLoan = BookLoan::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $lastNumber = 0;
        if ($lastLoan && preg_match('/LN-' . $year . '-(\d+)/', $lastLoan->loan_num, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        $nextNumber = $lastNumber + 1;

        $data['loan_num'] = 'LN-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return $data;
    }
}
