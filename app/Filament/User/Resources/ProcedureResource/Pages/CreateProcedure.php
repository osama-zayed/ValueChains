<?php

namespace App\Filament\User\Resources\ProcedureResource\Pages;

use App\Filament\User\Resources\ProcedureResource;
use App\Models\Procedure;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProcedure extends CreateRecord
{
    protected static string $resource = ProcedureResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $data['user_id'] = auth()->user()->id;
        $user = Procedure::create($data);

        return $user;
    }
}