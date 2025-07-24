<?php

namespace App\Filament\Resources\ReportResource\Widgets;

use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\BookLoanItem;
use Livewire\Attributes\On;

use Livewire\Component;

class ReportStats extends BaseWidget
{
    public array $filters = [];

    protected int | string | array $columnSpan = 'full';

    #[On('filters-updated')]
    public function updateFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    protected function getCards(): array
    {
        $query = BookLoanItem::query();

        if (!empty($this->filters['Status']['value'] ?? null)) {
            $status = $this->filters['Status']['value'];
            if ($status === 'returned') {
                $query->whereNotNull('return_date');
            } elseif ($status === 'not_returned') {
                $query->whereNull('return_date');
            }
        }

        if (!empty($this->filters['Tanggal Pinjam']['from'] ?? null)) {
            $query->whereHas(
                'bookLoan',
                fn($q) =>
                $q->whereDate('loan_date', '>=', $this->filters['Tanggal Pinjam']['from'])
            );
        }

        if (!empty($this->filters['Tanggal Pinjam']['to'] ?? null)) {
            $query->whereHas(
                'bookLoan',
                fn($q) =>
                $q->whereDate('loan_date', '<=', $this->filters['Tanggal Pinjam']['to'])
            );
        }

        return [
            Card::make('Total', (clone $query)->count()),
            Card::make('Sudah Dikembalikan', (clone $query)->whereNotNull('return_date')->count())->color('success'),
            Card::make('Belum Dikembalikan', (clone $query)->whereNull('return_date')->count())->color('danger'),
        ];
    }
}
