<?php

namespace App\Filament\Guest\Resources;

use App\Filament\Guest\Resources\AttendanceResource\Pages;
use App\Filament\Guest\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
        return 'Menu Kehadiran';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('Pilih User')
                ->relationship('user', 'name')
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state) {
                        $user = \App\Models\User::find($state);
                        if ($user) {
                            $set('name', $user->name);
                            $set('phone', $user->phone ?? '');
                            $set('gender', $user->gender ?? '');
                            $set('profession', $user->profession ?? '');
                            $set('address', $user->address ?? '');
                        }
                    } else {
                        $set('name', '');
                        $set('phone', '');
                        $set('gender', '');
                        $set('profession', '');
                        $set('address', '');
                    }
                }),

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

            Forms\Components\TextInput::make('profession')
                ->label('Pekerjaan')
                ->maxLength(100)
                ->nullable(),

            Forms\Components\Textarea::make('address')
                ->label('Alamat')
                ->rows(2)
                ->nullable(),

            Forms\Components\TextInput::make('purpose')
                ->label('Tujuan Kunjungan')
                ->maxLength(255)
                ->default('Membaca Buku')
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
                Tables\Columns\TextColumn::make('profession')->label('Pekerjaan'),
                Tables\Columns\TextColumn::make('address')->label('Alamat')->limit(30),
                Tables\Columns\TextColumn::make('purpose')->label('Tujuan'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Waktu Masuk'),
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
