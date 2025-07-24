<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Book;
use App\Models\Member;
use App\Models\BookLoan;
use App\Models\BookLoanItem;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class AdminOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        return [
            Card::make('Total Member', Member::count())->icon('heroicon-o-users'),
            Card::make('User Baru Bulan Ini', User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count())->icon('heroicon-o-user-plus'),
            Card::make('Total Buku', Book::count())->icon('heroicon-o-book-open'),
            Card::make('Total Peminjaman Bulan Ini', BookLoan::whereBetween('loan_date', [$startOfMonth, $endOfMonth])->count())->icon('heroicon-o-archive-box-arrow-down'),
            Card::make('Total Pengembalian Bulan Ini', BookLoanItem::whereNotNull('return_date')->whereBetween('return_date', [$startOfMonth, $endOfMonth])->count())->icon('heroicon-o-arrow-uturn-left'),
            Card::make('Belum Dikembalikan', BookLoanItem::whereNull('return_date')->count())->color('danger')->icon('heroicon-o-exclamation-triangle'),
        ];
    }
}
