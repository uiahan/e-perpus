<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookLoanResource\Pages;
use App\Filament\Resources\BookLoanResource\RelationManagers;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BookLoanResource extends Resource
{
    protected static ?string $model = BookLoan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    public static function getNavigationGroup(): ?String
    {
        return 'Menu Peminjaman';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('loan_num')->disabled()->dehydrated(),
                DatePicker::make('due_date')->required(),
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
                    ->required(),
                Select::make('book_id')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('loan_num')->searchable(),
                TextColumn::make('member.name')->label('Member'),
                TextColumn::make('book.title')->label('Book'),
                TextColumn::make('status')->badge()->color(fn($state) => match ($state) {
                    'dipinjam' => 'warning',
                    'dikembalikan' => 'success',
                    'telat' => 'danger',
                    'hilang' => 'gray',
                }),
            ])
            ->filters([
                //
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
            //
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
