<?php

namespace App\Filament\Guest\Resources\AttendanceResource\Pages;

use App\Filament\Guest\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getFormActions(): array
    {
        return [
            ...(static::canCreateAnother() ? [
                $this->getCreateAnotherFormAction()->color('warning')
            ] : []),
        ];
    }
}
