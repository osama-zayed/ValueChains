<?php

namespace App\Filament\User\Resources\ActivityResource\Pages;

use App\Filament\User\Resources\ActivityResource;
use App\Models\Activity;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Services\UserService;

class CreateActivity extends CreateRecord
{
    protected static string $resource = ActivityResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $data['user_id'] = auth()->user()->id;
        $user = Activity::create($data);
        UserService::NotificationsAdmin('تم اضافة نشاط جديد من قبل المستخدم '.auth()->user()->name);
        UserService::userActivity('اضافة نشاط : ' . $data['name']);
        return $user;

    }


}
