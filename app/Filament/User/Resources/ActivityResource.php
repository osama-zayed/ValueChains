<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\ActivityResource\Pages;
use App\Filament\User\Resources\ActivityResource\RelationManagers;
use App\Models\Activity;
use App\Models\Chain;
use App\Models\Domain;
use App\Models\Project;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Alkoumi\LaravelHijriDate\Hijri;
use Carbon\Carbon;
class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?int $navigationSort = 5;
    protected static ?string $modelLabel = 'نشاط';
    protected static ?string $pluralLabel = 'الانشطة';

    public static function activityForm()
    {
        return [
            Forms\Components\Section::make([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('اسم النشاط')
                    ->maxLength(255),
                Forms\Components\TextInput::make('target_value')
                    ->required()
                    ->label('القيمة المستهدفة')
                    ->minValue(0)
                    ->numeric(),
                Forms\Components\TextInput::make('target_indicator')
                    ->required()
                    ->label('مؤاشر القيمة المستهدفة ')
                    ->maxLength(255),
                Forms\Components\TextInput::make('activity_weight')
                    ->required()
                    ->label('وزن النشاط')
                    ->maxValue(100)
                    ->minValue(0)
                    ->numeric(),
            ])->columns(2)->collapsed(2),
            Forms\Components\Section::make([
                Forms\Components\Select::make('domain_id')
                    ->label('المجال')
                    ->options(Domain::all()->pluck('name', 'id'))
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->required()
                    ->afterStateUpdated(fn (callable $set) => $set('chain_id', null)),
                Forms\Components\Select::make('chain_id')
                    ->label('السلسلة')
                    ->options(function (callable $get) {
                        $domainId = $get('domain_id');
                        if ($domainId) {
                            return Chain::where('user_id', auth()->user()->id)->where('domain_id', $domainId)->pluck('name', 'id');
                        }
                        return Chain::where('user_id', auth()->user()->id)->pluck('name', 'id');
                    })
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->required()
                    ->afterStateUpdated(fn (callable $set) => $set('project_id', null)),

                Forms\Components\Select::make('project_id')
                    ->label('المشروع')
                    ->options(function (callable $get) {
                        $chainId = $get('chain_id');
                        if ($chainId) {
                            return Project::where('user_id', auth()->user()->id)->where('chain_id', $chainId)->pluck('name', 'id');
                        }
                        return Project::where('user_id', auth()->user()->id)->pluck('name', 'id');
                    })
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->required(),

            ])->columns(2)->collapsed(2),
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
                Tables\Columns\TextColumn::make('hijri_created_at')
                    ->label('سنة الاقرار')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('chain_id')
                    ->label('السلسلة')
                    ->multiple()
                    ->relationship('chain', 'name'),
                SelectFilter::make('project_id')
                    ->label('المشروع')
                    ->multiple()
                    ->relationship('project', 'name'),
                SelectFilter::make('hijri_created_at')
                    ->label('سنة الاقرار')
                    ->options([
                        Hijri::Date('o', Carbon::now()->subYears(6)) =>    Hijri::Date('o', Carbon::now()->subYears(6)),
                        Hijri::Date('o', Carbon::now()->subYears(5)) =>    Hijri::Date('o', Carbon::now()->subYears(5)),
                        Hijri::Date('o', Carbon::now()->subYears(4)) =>    Hijri::Date('o', Carbon::now()->subYears(4)),
                        Hijri::Date('o', Carbon::now()->subYears(3)) =>    Hijri::Date('o', Carbon::now()->subYears(3)),
                        Hijri::Date('o', Carbon::now()->subYears(2)) =>    Hijri::Date('o', Carbon::now()->subYears(2)),
                        Hijri::Date('o', Carbon::now()->subYears(1)) =>    Hijri::Date('o', Carbon::now()->subYears(1)),
                        Hijri::Date('o', Carbon::now()) =>    Hijri::Date('o', Carbon::now()),
                        Hijri::Date('o', Carbon::now()->addYears(1)) =>    Hijri::Date('o', Carbon::now()->addYears(1)),
                        Hijri::Date('o', Carbon::now()->addYears(2)) =>    Hijri::Date('o', Carbon::now()->addYears(2)),
                    ])
                    ->placeholder('اختر سنة الإقرار')
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
