<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookLoanResource\Pages;
use App\Filament\Resources\BookLoanResource\RelationManagers;
use App\Filament\Resources\ItemsRelationManagerResource\RelationManagers\ItemsRelationManager;
use App\Models\BookLoan;
use Filament\Forms\Components\Hidden;
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
use Filament\Tables\Actions\Action; // Import Action class
use Illuminate\Support\Facades\Http; // Import Http facade
use Illuminate\Support\Facades\Log; // Import Log facade
use Filament\Notifications\Notification; // Import Notification class

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
                DatePicker::make('loan_date')
                    ->default(now())
                    ->required()
                    ->label('Tanggal Peminjaman'),
                DatePicker::make('due_date')->required()->label('Tenggat Waktu'),

                Select::make('status')
                    ->options([
                        'Dalam Masa Pinjaman' => 'Dalam Masa Pinjaman',
                        'Sudah Dikembalikan' => 'Sudah Dikembalikan',
                        'Melebihi Tenggat Waktu' => 'Melebihi Tenggat Waktu',
                    ])
                    ->default('Dalam Masa Pinjaman'),
                Select::make('member_id')
                    ->relationship('member', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('book_ids')
                    ->label('Buku')
                    ->multiple()
                    ->relationship('books', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Hidden::make('user_id')
                    ->default(auth()->id())
                    ->dehydrated()
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
                TextColumn::make('due_date')
                    ->label('Tenggat Waktu')
                    ->date('d M Y'),
                TextColumn::make('status')->badge()->color(fn($state) => match ($state) {
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
            ->headerActions([ 
                Action::make('sendGlobalReminder')
                    ->label('Kirim Notifikasi Pengingat')
                    ->icon('heroicon-o-bell')
                    ->color('info')
                    ->action(function () {
                        try {
                            $username = env('REMINDER_API_USERNAME', 'fauzi');
                            $password = env('REMINDER_API_PASSWORD', 'Password123!');
                            $endpoint = env('REMINDER_API_ENDPOINT', 'https://n-fauzi.linkbee.id/webhook/execute-reminder');

                            $response = Http::withBasicAuth($username, $password)
                                ->post($endpoint);

                            if ($response->successful()) {
                                Notification::make()
                                    ->title('Notifikasi Pengingat Terkirim')
                                    ->body('API pengingat berhasil dipanggil.')
                                    ->success()
                                    ->send();
                                Log::info('Reminder API call successful (global)', ['response' => $response->json()]);
                            } else {
                                Notification::make()
                                    ->title('Gagal Mengirim Notifikasi Pengingat')
                                    ->body('API pengingat mengembalikan error: ' . $response->status())
                                    ->danger()
                                    ->send();
                                Log::error('Reminder API call failed (global)', ['status' => $response->status(), 'response' => $response->body()]);
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error Saat Mengirim Notifikasi')
                                ->body('Terjadi kesalahan: ' . $e->getMessage())
                                ->danger()
                                ->send();
                            Log::error('Exception during global reminder API call', ['error' => $e->getMessage()]);
                        }
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
