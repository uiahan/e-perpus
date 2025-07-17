<?php

namespace App\Filament\Resources\ItemsRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('book_id')
                    ->relationship('book', 'title')
                    ->disabledOn('edit')
                    ->required()
                    ->label('Buku'),
                DatePicker::make('return_date')
                    ->nullable()
                    ->label('Tanggal Dikembalikan'),
                TextInput::make('penalty')
                    ->label('Denda')
                    ->numeric()
                    ->prefix('Rp ')
                    ->nullable()
                    ->helperText('Otomatis diisi berdasarkan status, tapi bisa diubah'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'dipinjam' => 'dipinjam',
                        'dikembalikan' => 'dikembalikan',
                        'telat' => 'telat',
                        'hilang' => 'hilang',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === 'telat') {
                            $set('penalty', 20000);
                        } elseif ($state === 'hilang') {
                            $set('penalty', 100000);
                        } else {
                            $set('penalty', null);
                        }
                    }),
            ]);
    }


    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('book.title')->label('Book')->label('Buku'),
                TextColumn::make('status')->badge(),
                TextColumn::make('return_date')->label('Returned At')->label('Tanggal Dikembalikan'),
                TextColumn::make('penalty')
                    ->label('Denda')
                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
