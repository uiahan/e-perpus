<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'User';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'superadmin';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'superadmin';
    }

    public static function getNavigationGroup(): ?String
    {
        return 'Akun';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->label('Nama'),
                TextInput::make('email')->email()->required()->unique(ignoreRecord: true)->label('Email'),
                TextInput::make('phone')->label('Nomor HP')->tel(),
                Select::make('gender')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->label('Jenis Kelamin'),
                TextInput::make('profession')->label('Pekerjaan'),
                TextInput::make('address')->label('Alamat')->columnSpanFull(),

                Select::make('role')
                    ->options([
                        'superadmin' => 'Superadmin',
                        'admin' => 'Admin',
                        'member' => 'Member',
                    ])
                    ->default('member')
                    ->required(),

                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(function ($state) {
                        return filled($state) ? Hash::make($state) : null;
                    })
                    ->required(fn($context) => $context === 'create')
                    ->label('Password Baru')
                    ->placeholder(fn($context) => $context === 'edit' ? 'Kosongkan jika tidak ingin mengubah password' : null)
                    ->dehydrated(fn($state) => filled($state)),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->label('Nama Lengkap'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('phone')->label('Nomor HP'),
                TextColumn::make('gender')->label('Gender')->formatStateUsing(fn($state) => $state === 'L' ? 'Laki-laki' : ($state === 'P' ? 'Perempuan' : '-')),
                TextColumn::make('profession')->label('Pekerjaan')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('address')->label('Alamat')->limit(30)->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('role')->badge()->label('Role'),
                TextColumn::make('created_at')->date()->label('Bergabung Pada')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
                Tables\Actions\Action::make('promote')
                    ->databaseTransaction()
                    ->label('Promote')
                    ->icon('heroicon-o-arrow-up')
                    ->color('success')
                    ->requiresConfirmation()
                    ->disabled(fn(User $record) => $record->role != 'guest')
                    ->action(function (User $record, Tables\Actions\Action $action) {
                        $record->update(['role' => 'member']);
                        $record->member()->create($record->attributesToArray() + [
                            'birth_date' => now(),
                        ]);
                        return $action->success();
                    })
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
