<?php

namespace App\Filament\Resources\ReportResource\Widgets;

use App\Models\BookLoanItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ReportOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Peminjaman', BookLoanItem::count())->icon('heroicon-o-archive-box-arrow-down'),
            Card::make('Sudah Dikembalikan', BookLoanItem::whereNotNull('return_date')->count())->color('success')->icon('heroicon-o-arrow-uturn-left'),
            Card::make('Belum Dikembalikan', BookLoanItem::whereNull('return_date')->count())->color('danger')->icon('heroicon-o-exclamation-triangle'),
        ];
    }
}
