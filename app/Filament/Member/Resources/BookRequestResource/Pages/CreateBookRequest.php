<?php

namespace App\Filament\Member\Resources\BookRequestResource\Pages;

use App\Filament\Member\Resources\BookRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookRequest extends CreateRecord
{
    protected static string $resource = BookRequestResource::class;
}
