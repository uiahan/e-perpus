<?php

namespace App\Filament\Member\Resources;

use App\Filament\Member\Resources\BookLoanResource\Pages;
use App\Filament\Member\Resources\BookLoanResource\Pages\CreateBookLoan;
use App\Filament\Member\Resources\BookLoanResource\Pages\ListBookLoans;
use App\Filament\Member\Resources\BookLoanResource\Pages\EditBookLoan;
use App\Filament\Member\Resources\BookLoanResource\RelationManagers;
use App\Models\BookLoan;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class BookLoanResource extends Resource
{
    protected static ?string $model = BookLoan::class;

    protected static ?string $navigationLabel = 'Peminjaman Buku';
    protected static ?string $label = "Peminjaman Buku";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function generateLoanNumber(): string
    {
        $year = now()->format('Y');
        $latest = BookLoan::whereYear('created_at', $year)
            ->orderByDesc('created_at')
            ->first();

        $number = 1;
        if ($latest) {
            $lastNum = (int) str_replace('LN-' . $year . '-', '', $latest->loan_num);
            $number = $lastNum + 1;
        }

        return 'LN-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['member_id'] = auth()->user()->member->id;

        return $data;
    }

    public static function afterCreate($record, array $data): void
    {
        Log::info('afterCreate start', [
            'loan_id' => $record->id,
            'book_ids' => $data['book_ids'] ?? null,
        ]);

        $bookIds = $data['book_ids'] ?? [];

        foreach ($bookIds as $bookId) {
            try {
                $record->items()->create([
                    'book_id' => $bookId,
                ]);
            } catch (\Exception $e) {
                Log::error('Error create item', [
                    'book_id' => $bookId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('afterCreate finished', ['loan_id' => $record->id]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('loan_num')
                    ->default(fn() => BookLoanResource::generateLoanNumber())
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->label('Nomor Peminjaman'),
                Hidden::make('loan_date')
                    ->default(now())
                    ->required()
                    ->label('Tanggal Peminjaman'),
                Select::make('book_ids')
                    ->label('Buku')
                    ->multiple()
                    ->maxItems(3)
                    ->relationship('books', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('member_id', auth()->user()->member->id);
            })
            ->columns([
                TextColumn::make('loan_num')->searchable(),
                TextColumn::make('member.name')->label('Member'),
                TextColumn::make('books_list')
                    ->label('Books')
                    ->html()
                    ->getStateUsing(function ($record) {
                        $books = $record->books->pluck('title')->map(fn($title) => "- {$title}")->implode('<br>');

                        return $books;
                    }),
                TextColumn::make('due_date')
                    ->label('Pengembalian')
                    ->date('d M Y'),
                TextColumn::make('status')->badge()->color(fn($state) => match ($state) {
                    'Pengajuan' => 'primary',
                    'Dalam Masa Pinjaman' => 'warning',
                    'Sudah Dikembalikan' => 'success',
                    'Melebihi Tenggat Waktu' => 'danger',
                }),
                TextColumn::make('user.name')
                    ->label('Disetujui Oleh')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return  [
            'index' => ListBookLoans::route('/'),
            'create' => CreateBookLoan::route('/create'),
        ];
    }
}
