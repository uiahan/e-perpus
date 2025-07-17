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
                    ->required(),
                DatePicker::make('return_date')->label('Return Date')->nullable(),
                TextInput::make('penalty')->numeric()->nullable(),
                Select::make('status')
                    ->options([
                        'dipinjam' => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                        'telat' => 'Terlambat',
                        'hilang' => 'Hilang',
                    ])
                    ->required(),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('book.title')->label('Book'),
                TextColumn::make('status')->badge(),
                TextColumn::make('return_date')->label('Returned At'),
                TextColumn::make('penalty')->label('Penalty'),
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
