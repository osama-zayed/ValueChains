<?php

namespace App\Filament\User\Resources\ProjectResource\Pages;

use App\Filament\User\Resources\ProjectResource;
use App\Models\Project;
use App\Services\UserService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    protected function authorizeAccess(): void
    {
        if (auth()->user()->id != $this->getRecord()->user_id) {
            abort(404);
        }
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data['user_id'] = auth()->user()->id;
        $record->update($data);
        UserService::NotificationsAdmin('تم تعديل مشروع من قبل المستخدم ' . auth()->user()->name);
        UserService::userActivity('تعديل مشروع : ' . $data['name']);
        return $record;
    }
}
