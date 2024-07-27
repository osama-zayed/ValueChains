<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProjectResource\Pages;
use App\Filament\Admin\Resources\ProjectResource\RelationManagers;
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
use App\Models\Ring;
use Carbon\Carbon;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?int $navigationSort = 4;
    protected static ?string $modelLabel = 'المشروع';
    protected static ?string $pluralLabel = 'المشاريع';
    public static function projectForm()
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->label('اسم المشروع')
                ->maxLength(255),
            Forms\Components\Select::make('domain_id')
                ->label('المجال')
                ->options(Domain::all()->pluck('name', 'id'))
                ->reactive()
                ->searchable()
                ->preload()
                ->required()
                ->createOptionForm(
                    DomainResource::domainForm()
                )
                ->afterStateUpdated(fn (callable $set) => $set('chain_id', null)),
            Forms\Components\Select::make('ring_id')
                ->label('الحلقة')
                ->options(Ring::all()->pluck('name', 'id'))
                ->reactive()
                ->searchable()
                ->preload()
                ->required()
                ->createOptionForm(
                    RingResource::RingForm()
                )
                ->afterStateUpdated(fn (callable $set) => $set('chain_id', null)),
            Forms\Components\Select::make('chain_id')
                ->label('السلسلة')
                ->options(function (callable $get) {
                    $domainId = $get('domain_id');
                    if ($domainId) {
                        return Chain::where('domain_id', $domainId)->pluck('name', 'id');
                    }
                    return Chain::all()->pluck('name', 'id');
                })
                ->reactive()
                ->searchable()
                ->preload()
                ->createOptionForm(
                    ChainResource::chainForm()
                )
                ->required(),

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
            ->schema(self::projectForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المشروع')
                    ->searchable(),
                Tables\Columns\TextColumn::make('domain.name')
                    ->numeric()
                    ->label('المجال')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ring.name')
                    ->label('الحلقة')
                    ->label('المجال')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('chain.name')
                    ->numeric()
                    ->label('السلسلة')
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
            'index' => Pages\ListProjects::route('/'),
            // 'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
