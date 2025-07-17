<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        $plainpassword= Str::random(10);
        $user = User::create([
            'name'  => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($plainpassword),
        ]);

        $data['user_id'] = $user->id;

        return $data;
    }
}
