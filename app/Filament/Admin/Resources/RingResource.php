<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RingResource\Pages;
use App\Filament\Admin\Resources\RingResource\RelationManagers;
use App\Models\Ring;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class RingResource extends Resource
{
    protected static ?string $model = Ring::class;

    protected static ?string $navigationIcon = 'heroicon-o-stop-circle';

    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'الحلقة';
    protected static ?string $pluralLabel = 'الحلقات';

    public static function RingForm()
    {
        return [Forms\Components\TextInput::make('name')
            ->required()
            ->label('اسم الحلقة')
            ->columnSpanFull()
            ->maxLength(255)];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::RingForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الحلقة')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('وقت الاضافة')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('وقت التعديل')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRings::route('/'),
            // 'create' => Pages\CreateRing::route('/create'),
            'view' => Pages\ViewRing::route('/{record}'),
            'edit' => Pages\EditRing::route('/{record}/edit'),
        ];
    }
}
