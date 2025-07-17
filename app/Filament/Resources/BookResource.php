<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction as ActionsDeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Buku';

    public static function getNavigationGroup(): ?String
    {
        return 'Menu Kelola';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('cover')->image()->directory('book-covers')
                ->image()
                ->directory('book_covers')
                ->disk('public')
                ->visibility('public')
                ->imagePreviewHeight('150')
                ->maxSize(5120)
                ->imageEditor()
                ->previewable()
                ->nullable()->label('Cover'),
                Textarea::make('description')->nullable()->label('Deskripsi'),
                TextInput::make('title')->required()->label('Judul'),
                Select::make('categories')
                    ->multiple()
                    ->relationship('categories', 'category_name')
                    ->searchable()
                    ->preload()
                    ->required()->label('Kategori'),
                TextInput::make('author')->required()->label('Pengarang'),
                TextInput::make('publisher')->required()->label('Penerbit'),
                TextInput::make('year')->numeric()->required()->label('Tahun'),
                TextInput::make('code')->nullable()->unique(ignoreRecord: true)->label('Kode'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover')
                    ->label('Cover')
                    ->disk('public')
                    ->width(60)
                    ->height(60),
                TextColumn::make('title')->searchable()->label('Judul'),
                TextColumn::make('author')->label('Pengarang'),
                TextColumn::make('categories.category_name')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Novel' => 'primary',
                        'Teknologi' => 'success',
                        'Sejarah' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('year')->label('Tahun'),
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
