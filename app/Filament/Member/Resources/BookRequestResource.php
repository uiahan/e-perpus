<?php

namespace App\Filament\Member\Resources;

use App\Filament\Member\Resources\BookRequestResource\Pages;
use App\Filament\Member\Resources\BookRequestResource\RelationManagers;
use App\Models\BookRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookRequestResource extends Resource
{
    protected static ?string $model = BookRequest::class;

    protected static ?string $navigationLabel = 'Permintaan Buku';
    protected static ?string $label = "Permintaan Buku";


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->rows(10)
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_proceeded')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn(BookRequest $record) => !$record->is_proceeded),
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
        return [
            'index' => Pages\ListBookRequests::route('/'),
            'create' => Pages\CreateBookRequest::route('/create'),
            'edit' => Pages\EditBookRequest::route('/{record}/edit'),
            'view' => Pages\ViewBookRequest::route('/{record}'),
        ];
    }
}
