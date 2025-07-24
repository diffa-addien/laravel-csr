<?php

namespace App\Filament\Clusters\StakeholderPerencanaan\Resources;

use App\Filament\Clusters\StakeholderPerencanaan;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderEngagementPlanResource\Pages;
use App\Filament\Clusters\StakeholderPerencanaan\Resources\StkholderEngagementPlanResource\RelationManagers;
use App\Models\StkholderEngagementPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\MorphToSelect;
use App\Models\StkholderProfilInternal;
use App\Models\StkholderProfilExternal;
use App\Filament\Traits\HasResourcePermissions;

class StkholderEngagementPlanResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'stakeholder';
    protected static ?string $model = StkholderEngagementPlan::class;

    protected static ?string $cluster = StakeholderPerencanaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Engagement Plan';
    protected static ?string $pluralModelLabel = 'Engagement Plan';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Stakeholder Details')
                    ->schema([
                        // Menggunakan relasi 'stakeholder'
                        MorphToSelect::make('stakeholder')
                            ->label('Stakeholder')
                            ->types([
                                MorphToSelect\Type::make(StkholderProfilInternal::class)
                                    ->titleAttribute('nama')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => '[Internal] ' . $record->nama),
                                MorphToSelect\Type::make(StkholderProfilExternal::class)
                                    ->titleAttribute('nama')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => '[External] ' . $record->nama),
                            ])
                            // ->searchable()
                            ->required(),

                        Select::make('influence_level')
                            ->label('Influence Level')
                            ->options([
                                'Very high' => 'Very High',
                                'High' => 'High',
                                'Medium' => 'Medium',
                                'Low' => 'Low',
                                'Very low' => 'Very Low',
                            ])
                            ->required(),

                        Select::make('interest_level')
                            ->label('Interest Level')
                            ->options([
                                'Leading' => 'Leading',
                                'Supporting' => 'Supporting',
                                'Neutral' => 'Neutral',
                                'Resistant' => 'Resistant',
                                'Unaware' => 'Unaware',
                            ])
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Communication Plan')
                    ->schema([
                        Select::make('frequency')->options(['Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Quarterly' => 'Quarterly']),
                        Select::make('channel')->options(['Slack' => 'Slack', 'Asana' => 'Asana', 'Zoom' => 'Zoom', 'Email' => 'Email']),
                        Select::make('info_type')->label('Information Type')->options(['Status update' => 'Status Update', 'Progress to goal' => 'Progress to Goal', 'Brainstorming session' => 'Brainstorming Session', 'Strategic planning' => 'Strategic Planning']),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menggunakan relasi 'stakeholder'
                TextColumn::make('stakeholder.nama')
                    ->label('Stakeholder')
                    ->formatStateUsing(function ($state, $record) {
                        $prefix = '';
                        // Menggunakan stakeholder_type
                        if ($record->stakeholder_type === StkholderProfilInternal::class) {
                            $prefix = '[Internal] ';
                        } elseif ($record->stakeholder_type === StkholderProfilExternal::class) {
                            $prefix = '[External] ';
                        }
                        return $prefix . $state;
                    })
                    ->searchable(query: function ($query, $search) {
                        // Menggunakan relasi 'stakeholder'
                        return $query
                            ->orWhereHasMorph('stakeholder', [StkholderProfilInternal::class, StkholderProfilExternal::class], function ($q) use ($search) {
                                $q->where('nama', 'like', "%{$search}%");
                            });
                    })
                    ->sortable(),

                TextColumn::make('influence_level')->label('Influence')->searchable()->sortable(),
                TextColumn::make('interest_level')->label('Interest')->searchable()->sortable(),
                TextColumn::make('frequency')->label('Frequency')->searchable()->sortable(),
                TextColumn::make('channel')->label('Channel')->searchable()->sortable(),
                TextColumn::make('info_type')->label('Info Type')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStkholderEngagementPlans::route('/'),
            // 'create' => Pages\CreateStkholderEngagementPlan::route('/create'),
            // 'edit' => Pages\EditStkholderEngagementPlan::route('/{record}/edit'),
        ];
    }
}
