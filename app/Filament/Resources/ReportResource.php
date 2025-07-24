<?php

namespace App\Filament\Resources;

use App\Models\BookLoanItem;
use App\Filament\Resources\ReportResource\Pages;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ReportResource extends Resource
{
    protected static ?string $model = BookLoanItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'Laporan Peminjaman';
    protected static ?string $navigationGroup = 'Laporan';

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('book.title')->label('Judul Buku')->searchable(),
                TextColumn::make('bookLoan.member.name')->label('Nama Member')->searchable(),
                TextColumn::make('bookLoan.loan_date')->label('Tanggal Pinjam')->date('d M Y'),
                TextColumn::make('return_date')->label('Tanggal Kembali')->date('d M Y')->placeholder('Belum Kembali'),
                TextColumn::make('bookLoan.user.name')
                    ->label('Disetujui Oleh')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')->label('Status')->badge()->color(fn($record) => $record->return_date ? 'success' : 'danger'),
            ])
            ->filters([
                Filter::make('Tanggal Pinjam')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('to')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereHas('bookLoan', fn($q2) => $q2->whereDate('loan_date', '>=', $data['from'])))
                            ->when($data['to'], fn($q) => $q->whereHas('bookLoan', fn($q2) => $q2->whereDate('loan_date', '<=', $data['to'])));
                    }),
                Filter::make('Status')
                    ->label('Status Pengembalian')
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['value'] === 'returned', fn($q) => $q->whereNotNull('return_date'))
                            ->when($data['value'] === 'not_returned', fn($q) => $q->whereNull('return_date'));
                    })
                    ->form([
                        Forms\Components\Select::make('value')
                            ->label('Status')
                            ->options([
                                'returned' => 'Sudah Dikembalikan',
                                'not_returned' => 'Belum Dikembalikan',
                            ])
                            ->placeholder('Pilih status')
                    ]),

            ])
            ->defaultSort('id', 'desc')
            ->headerActions([
                Action::make('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->label('Export Excel')
                    ->url(function () {
                        $from = request()->input('tableFilters.Tanggal%20Pinjam.from');
                        $to = request()->input('tableFilters.Tanggal%20Pinjam.to');

                        return route('export.excel', [
                            'from' => $from,
                            'to' => $to,
                        ]);
                    }, shouldOpenInNewTab: true)
            ])

            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
        ];
    }
}
