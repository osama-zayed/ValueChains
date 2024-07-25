<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProcedureResource\Pages;
use App\Filament\Admin\Resources\ProcedureResource\RelationManagers;
use App\Models\Procedure;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcedureResource extends Resource
{
    protected static ?string $model = Procedure::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 6;
    protected static ?string $modelLabel = 'الاجراء';
    protected static ?string $pluralLabel = 'الاجراءات';
    public static function projectForm()
    {
        return [
            Forms\Components\Section::make([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('اسم الاجراء')
                    ->maxLength(255),
                Forms\Components\TextInput::make('procedure_weight')
                    ->required()
                    ->label('وزن الاجراء')
                    ->numeric(),
                Forms\Components\TextInput::make('procedure_duration_days')
                    ->required()
                    ->label('مدة الاجراء بالايام')
                    ->numeric(),
                Forms\Components\TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->label('التكلفة')
                    ->prefix('$'),
                Forms\Components\DatePicker::make('procedure_start_date')
                    ->label('بدء تنفيذ الاجراء')
                    ->required(),
                Forms\Components\DatePicker::make('procedure_end_date')
                    ->label('نهاية تنفيذ الاجراء')
                    ->required(),

                Forms\Components\TextInput::make('funding_source')
                    ->required()
                    ->label('مصدر التمويل')
                    ->columnSpanFull()
                    ->maxLength(255),
            ])->columns(2)->collapsed(2),
            Forms\Components\Section::make([

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
                        ChainResource::chainForm()
                    ),
                Forms\Components\Select::make('activity_id')
                    ->relationship('activity', titleAttribute: 'name')
                    ->label('النشاط')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm(
                        ActivityResource::activityForm()
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
            ])->columns(2)->collapsed(2),

            Forms\Components\Toggle::make('status')
                ->label('الحالة')
                ->required(),

            Forms\Components\Section::make([
                Forms\Components\FileUpload::make('attached_file')
                    ->label('صور للمنتج')
                    ->required()
                    ->nullable()
                    ->directory('attached_file')
            ]),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::projectForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاجراء')
                    ->searchable(),
                Tables\Columns\TextColumn::make('procedure_weight')
                    ->numeric()
                    ->label('وزن الاجراء')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('procedure_duration_days')
                    ->numeric()
                    ->label('مدة تنفيذ الاجراء بالايام')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('procedure_start_date')
                    ->date()
                    ->label('بدء تنفيذ الاجراء')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('procedure_end_date')
                    ->date()
                    ->label('نهاية تنفيذ الاجراء')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cost')
                    ->money()
                    ->label('التكلفة')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('funding_source')
                    ->label('مصدر التمويل')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('domain.name')
                    ->label('المجال')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('chain.name')
                    ->label('السلسلة')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('chain.Goals')
                    ->label('الاهداف')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('المشروع')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('activity.name')
                    ->label('النشاط')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('activity.target_value')
                    ->numeric()
                    ->label('وزن النشاط')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('activity.target_indicator')
                    ->numeric()
                    ->label('مؤاشر القيمة المستهدفة ')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('activity.activity_weight')
                    ->numeric()
                    ->label('وزن النشاط')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->label('المستخدم')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('status')
                    ->label('الحالة')
                    ->boolean()
                    ->action(function ($record, $column) {
                        $name = $column->getName();
                        $record->update([
                            $name => !$record->$name
                        ]);
                    }),
                // Tables\Columns\TextColumn::make('hijri_created_at')
                //     ->searchable(),
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
            SelectFilter::make('activity_id')
                ->label('النشاط')
                ->multiple()
                ->relationship('activity', 'name'),
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
            'index' => Pages\ListProcedures::route('/'),
            'create' => Pages\CreateProcedure::route('/create'),
            'view' => Pages\ViewProcedure::route('/{record}'),
            'edit' => Pages\EditProcedure::route('/{record}/edit'),
        ];
    }
}
