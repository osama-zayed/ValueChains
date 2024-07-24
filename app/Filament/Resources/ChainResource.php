<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChainResource\Pages;
use App\Filament\Resources\ChainResource\RelationManagers;
use App\Models\Chain;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                ->relationship('domain', titleAttribute: 'name')
                ->label('المجال')
                ->searchable()
                ->preload()
                ->required()
                ->createOptionForm([
                    DomainResource::domainForm()
                ]),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('domain.name')
                    ->numeric()
                    ->label('المجال')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->label('المستخدم')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hijri_created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListChains::route('/'),
            'create' => Pages\CreateChain::route('/create'),
            'view' => Pages\ViewChain::route('/{record}'),
            'edit' => Pages\EditChain::route('/{record}/edit'),
        ];
    }
}
