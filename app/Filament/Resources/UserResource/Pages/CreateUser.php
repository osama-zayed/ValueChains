<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $data['user_type'] = 'user';
        $user = User::create($data);

        return $user;
    }
}
