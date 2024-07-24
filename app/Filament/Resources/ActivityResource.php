<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\RelationManagers;
use App\Models\Activity;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?int $navigationSort = 5;
    protected static ?string $modelLabel = 'نشاط';
    protected static ?string $pluralLabel = 'الانشطة';

    public static function activityForm()
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->label('اسم النشاط')
                ->maxLength(255),
            Forms\Components\TextInput::make('target_value')
                ->required()
                ->label('القيمة المستهدفة')
                ->numeric(),
            Forms\Components\TextInput::make('target_indicator')
                ->required()
                ->label('مؤاشر القيمة المستهدفة ')
                ->maxLength(255),
            Forms\Components\TextInput::make('activity_weight')
                ->required()
                ->label('وزن النشاط')
                ->numeric(),
            Forms\Components\Select::make('domain_id')
                ->relationship('domain', titleAttribute: 'name')
                ->label('المجال')
                ->searchable()
                ->preload()
                ->required()
                ->createOptionForm(
                    DomainResource::domainForm()
                ),
            Forms\Components\Select::make('chain_id')
                ->relationship('chain', titleAttribute: 'name')
                ->label('السلسلة')
                ->searchable()
                ->preload()
                ->required()
                ->createOptionForm(
                    ChainResource::chainForm()
                ),
            Forms\Components\Select::make('project_id')
                ->relationship('project', titleAttribute: 'name')
                ->label('المشروع')
                ->searchable()
                ->preload()
                ->required()
                ->createOptionForm(
                    ProjectResource::projectForm()
                ),

            Forms\Components\Select::make('user_id')
                ->relationship('user', titleAttribute: 'name')
                ->label('المستخدم')
                ->searchable()
                ->preload()
                ->options(function () {
                    return User::where('user_type', 'user')->pluck('name', 'id');
                })
                ->required(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                self::activityForm()
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم النشاط')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_value')
                    ->numeric()
                    ->label('القيمة المستهدفة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('target_indicator')
                    ->label('مؤاشر القيمة المستهدفة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('activity_weight')
                    ->label('وزن النشاط')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('domain.name')
                    ->numeric()
                    ->label('المجال')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('chain.name')
                    ->numeric()
                    ->label('السلسلة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->numeric()
                    ->label('المشروع')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->label('المستخدم')
                    ->searchable()
                    ->sortable(),
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
                SelectFilter::make('domain_id')
                    ->label('المجال')
                    ->multiple()
                    ->relationship('domain', 'name'),
                SelectFilter::make('user_id')
                    ->label('المستخدم')
                    ->multiple()
                    ->options(function () {
                        return User::where('user_type', 'user')->pluck('name', 'id');
                    })
                    ->relationship('user', 'name'),
                SelectFilter::make('chain_id')
                    ->label('السلسلة')
                    ->multiple()
                    ->relationship('chain', 'name'),
                SelectFilter::make('project_id')
                    ->label('المشروع')
                    ->multiple()
                    ->relationship('project', 'name'),
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
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'view' => Pages\ViewActivity::route('/{record}'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}
