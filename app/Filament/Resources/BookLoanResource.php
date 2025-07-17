<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookLoanResource\Pages;
use App\Filament\Resources\BookLoanResource\RelationManagers;
use App\Filament\Resources\ItemsRelationManagerResource\RelationManagers\ItemsRelationManager;
use App\Models\BookLoan;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BookLoanResource extends Resource
{
    protected static ?string $model = BookLoan::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    protected static ?string $navigationLabel = 'Peminjaman Buku';

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
        $data['loan_num'] = self::generateLoanNumber();
        return $data;
    }

    public static function afterCreate($record, array $data): void
    {
        $bookIds = $data['book_ids'] ?? [];

        foreach ($bookIds as $bookId) {
            $record->items()->create([
                'book_id' => $bookId,
            ]);
        }
    }

    public static function getNavigationGroup(): ?String
    {
        return 'Menu Peminjaman';
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
                DatePicker::make('due_date')->required()->label('Tenggat Waktu'),
                TextInput::make('penalty')->numeric()->nullable(),
                DatePicker::make('return_date')->nullable(),
                Select::make('status')
                    ->options([
                        'dipinjam' => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                        'telat' => 'Terlambat',
                        'hilang' => 'Hilang',
                    ])
                    ->default('dipinjam'),
                Select::make('member_id')
                    ->relationship('member', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('book_ids')
                    ->label('Books')
                    ->multiple()
                    ->relationship('books', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
            ]);
    }

    public static function getTableQuery(): Builder
    {
        return BookLoan::query()
            ->select('loan_num', 'member_id', 'due_date')
            ->groupBy('loan_num', 'member_id', 'due_date');
    }


    public static function table(Table $table): Table
    {
        return $table
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
                TextColumn::make('status')->badge()->color(fn($state) => match ($state) {
                    'dipinjam' => 'warning',
                    'dikembalikan' => 'success',
                    'telat' => 'danger',
                    'hilang' => 'gray',
                }),
            ])
            ->filters([
                Filter::make('loan_num')
                    ->form([
                        TextInput::make('loan_num')->label('Loan Number'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['loan_num'],
                            fn($q) =>
                            $q->where('loan_num', 'like', '%' . $data['loan_num'] . '%')
                        );
                    }),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
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
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookLoans::route('/'),
            'create' => Pages\CreateBookLoan::route('/create'),
            'edit' => Pages\EditBookLoan::route('/{record}/edit'),
        ];
    }
}
