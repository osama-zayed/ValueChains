<?php

namespace App\Filament\User\Resources\ProcedureResource\Pages;

use App\Filament\User\Resources\ProcedureResource;
use App\Models\Procedure;
use App\Services\UserService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProcedure extends EditRecord
{
    protected static string $resource = ProcedureResource::class;

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
        $data['status'] = 0;
        $user = Procedure::create($data);
        UserService::NotificationsAdmin('تم تعديل اجراء من قبل المستخدم ' . auth()->user()->name);
        UserService::userActivity('تعديل اجراء : ' . $data['name']);
        return $user;
    }
}
