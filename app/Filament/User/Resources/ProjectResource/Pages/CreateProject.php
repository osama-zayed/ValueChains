<?php

namespace App\Filament\User\Resources\ProjectResource\Pages;

use App\Filament\User\Resources\ProjectResource;
use App\Models\Project;
use App\Services\UserService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $data['user_id'] = auth()->user()->id;
        $user = Project::create($data);
        UserService::NotificationsAdmin('تم اضافة مشروع جديد من قبل المستخدم '.auth()->user()->name);
        UserService::userActivity('اضافة مشروع : ' . $data['name']);        
        return $user;
    }
}
