<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProcedureResource\Pages;
use App\Filament\Admin\Resources\ProcedureResource\RelationManagers;
use App\Models\Activity;
use App\Models\Chain;
use App\Models\Domain;
use App\Models\Procedure;
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
use Filament\Tables\Actions\BulkAction;
use Mpdf\Mpdf;
use PDF;
use Alkoumi\LaravelHijriDate\Hijri;
use App\Models\Ring;
use Carbon\Carbon;

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
                    $ringId = $get('ring_id');
                    if ($domainId && $ringId) {
                        return Chain::whereHas('domains', function ($query) use ($domainId) {
                            $query->where('domains.id', $domainId);
                        })
                        ->whereHas('rings', function ($query) use ($ringId) {
                            $query->where('rings.id', $ringId);
                        })
                        ->pluck('name', 'id');
                    } elseif ($domainId) {
                        return Chain::whereHas('domains', function ($query) use ($domainId) {
                            $query->where('domains.id', $domainId);
                        })
                        ->pluck('name', 'id');
                    } elseif ($ringId) {
                        return Chain::whereHas('rings', function ($query) use ($ringId) {
                            $query->where('rings.id', $ringId);
                        })
                        ->pluck('name', 'id');
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
                Forms\Components\Select::make('project_id')
                    ->label('المشروع')
                    ->options(function (callable $get) {
                        $chainId = $get('chain_id');
                        if ($chainId) {
                            return Project::where('chain_id', $chainId)->pluck('name', 'id');
                        }
                        return Project::all()->pluck('name', 'id');
                    })
                    ->reactive()
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm(
                        ProjectResource::projectForm()
                    )
                    ->afterStateUpdated(fn (callable $set) => $set('activity_id', null)),

                Forms\Components\Select::make('activity_id')
                    ->relationship('activity', titleAttribute: 'name')
                    ->label('النشاط')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(function (callable $get) {
                        $chainId = $get('project_id');
                        if ($chainId) {
                            return Activity::where('project_id', $chainId)->pluck('name', 'id');
                        }
                        return Activity::all()->pluck('name', 'id');
                    })
                    ->createOptionForm(
                        ActivityResource::activityForm()
                    )
                    ->reactive(),

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
                    ->label('مرفق المصفوفة')
                    ->required()
                    ->nullable()
                    ->downloadable()
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
                Tables\Columns\TextColumn::make('ring.name')
                    ->label('الحلقة')
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
                    SelectFilter::make('ring_id')
                    ->label('الحلقة')
                    ->multiple()
                    ->relationship('ring', 'name'),
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
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('print_pdf')
                        ->label('طباعة ك PDF')
                        ->action(function ($records) {
                            return self::printPdf($records);
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function printPdf($records)
    {
        // Get the IDs from the records
        $recordIds = $records->pluck('id')->toArray();
        return redirect()->route('report', ['data' => $recordIds]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcedures::route('/'),
            // 'create' => Pages\CreateProcedure::route('/create'),
            'view' => Pages\ViewProcedure::route('/{record}'),
            // 'edit' => Pages\EditProcedure::route('/{record}/edit'),
        ];
    }
}
