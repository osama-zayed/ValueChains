<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcedureResource\Pages;
use App\Filament\Resources\ProcedureResource\RelationManagers;
use App\Models\Procedure;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcedureResource extends Resource
{
    protected static ?string $model = Procedure::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

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
                Tables\Columns\TextColumn::make('domain_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('chain_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('activity_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hijri_created_at')
                    ->searchable(),
                Tables\Columns\TextColumn::make('procedure_weight')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('procedure_duration_days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('procedure_start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('procedure_end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('funding_source')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('attached_file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            'index' => Pages\ListProcedures::route('/'),
            'create' => Pages\CreateProcedure::route('/create'),
            'view' => Pages\ViewProcedure::route('/{record}'),
            'edit' => Pages\EditProcedure::route('/{record}/edit'),
        ];
    }
}
