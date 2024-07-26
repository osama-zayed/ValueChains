<?php

namespace App\Filament\User\Resources\ProcedureResource\Pages;

use App\Filament\User\Resources\ProcedureResource;
use App\Models\Procedure;
use App\Services\UserService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProcedure extends CreateRecord
{
    protected static string $resource = ProcedureResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $data['user_id'] = auth()->user()->id;
        $data['status'] = 0;
        $user = Procedure::create($data);
        UserService::NotificationsAdmin('تم اضافة اجراء جديد من قبل المستخدم ' . auth()->user()->name);
        UserService::userActivity('اضافة اجراء : ' . $data['name']);
        return $user;
    }
}
