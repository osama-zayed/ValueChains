<?php

namespace App\Filament\User\Resources\ActivityResource\Pages;

use App\Filament\User\Resources\ActivityResource;
use App\Models\Activity;
use App\Services\UserService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditActivity extends EditRecord
{
    protected static string $resource = ActivityResource::class;

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
        UserService::NotificationsAdmin('تم تعديل نشاط من قبل المستخدم ' . auth()->user()->name);
        UserService::userActivity('تعديل نشاط : ' . $data['name']);
        return $record;
    }
}
