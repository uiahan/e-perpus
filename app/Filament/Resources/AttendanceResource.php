<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationLabel = 'Form Kehadiran';

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function getNavigationGroup(): ?String
    {
        return 'Kehadiran Tamu';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('phone')
                ->tel()
                ->maxLength(20),

            Forms\Components\Select::make('gender')
                ->options([
                    'L' => 'Laki-laki',
                    'P' => 'Perempuan',
                ])
                ->nullable(),

            Forms\Components\TextInput::make('purpose')
                ->label('Tujuan Kunjungan')
                ->maxLength(255)
                ->default('Membaca Buku')
                ->nullable(),

            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('gender')->label('JK'),
                Tables\Columns\TextColumn::make('purpose')->label('Tujuan'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Waktu Masuk'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('formKehadiran')
                    ->label('Form Kehadiran')
                    ->color('info')
                    ->icon('heroicon-o-pencil-square')
                    ->url('/guest/attendances/create') 
                    ->openUrlInNewTab(true)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
