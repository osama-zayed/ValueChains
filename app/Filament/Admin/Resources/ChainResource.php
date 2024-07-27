<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChainResource\Pages;
use App\Filament\Admin\Resources\ChainResource\RelationManagers;
use App\Models\Chain;
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

class ChainResource extends Resource
{
    protected static ?string $model = Chain::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'سلسلة';
    protected static ?string $pluralLabel = 'السلاسل';
    public static function chainForm()
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->label('اسم السلسلة')
                ->columnSpanFull()
                ->maxLength(255),
            Forms\Components\Textarea::make('Goals')
                ->required()
                ->label('الاهداف')
                ->maxLength(65535)
                ->columnSpanFull(),
            Forms\Components\Select::make('domain_id')
                ->relationship('domains', titleAttribute: 'name')
                ->label('المجال')
                ->multiple()
                ->preload()
                ->required()
                ->createOptionForm(
                    DomainResource::domainForm()
                ),
            Forms\Components\Select::make('ring_id')
                ->relationship('rings', titleAttribute: 'name')
                ->label('الحلقة')
                ->multiple()
                ->preload()
                ->required()
                ->createOptionForm(
                    RingResource::RingForm()
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
            ->schema(self::chainForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم السلسلة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('domains.name')
                    ->numeric()
                    ->label('المجال')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rings.name')
                    ->numeric()
                    ->label('الحلقة')
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
                    ->relationship('domains', 'name'),
                SelectFilter::make('ring_id')
                    ->label('الحلقة')
                    ->multiple()
                    ->relationship('rings', 'name'),
                SelectFilter::make('user_id')
                    ->label('المستخدم')
                    ->multiple()
                    ->options(function () {
                        return User::where('user_type', 'user')->pluck('name', 'id');
                    })
                    ->relationship('user', 'name'),
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
            'index' => Pages\ListChains::route('/'),
            // 'create' => Pages\CreateChain::route('/create'),
            'view' => Pages\ViewChain::route('/{record}'),
            'edit' => Pages\EditChain::route('/{record}/edit'),
        ];
    }
}
