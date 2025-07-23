<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class AttendanceForm extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = null;
    protected static ?string $title = 'Isi Form Kehadiran';
    protected static string $view = 'filament.pages.attendance-form';

    public ?array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Nama')->required(),
            Forms\Components\TextInput::make('phone')->label('Nomor HP')->tel(),
            Forms\Components\Select::make('gender')
                ->label('Jenis Kelamin')
                ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                ->required(),
            Forms\Components\TextInput::make('purpose')->label('Tujuan Kunjungan')->default('Membaca Buku')->required(),
        ])->statePath('data');
    }

    public function submit(): void
    {
        Attendance::create($this->form->getState());

        Notification::make()
            ->title('Berhasil!')
            ->body('Kehadiran berhasil dicatat.')
            ->success()
            ->send();

        $this->form->fill();
    }
}
