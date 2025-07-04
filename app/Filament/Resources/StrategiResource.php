<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrategiResource\Pages;
use App\Filament\Resources\StrategiResource\RelationManagers;
use App\Models\Strategi;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;

use App\Filament\Traits\HasResourcePermissions;

class StrategiResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = Strategi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $modelLabel = 'Data';
    protected static ?string $pluralModelLabel = 'Data Strategi';
    public static int $navigationGroupSort = 8;

    protected static ?string $navigationLabel = 'Strategi Program';
    protected static ?int $navigationSort = 5;

    public static function getPluralModelLabel(): string
    {
        return 'Daftar Strategi Program';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                RichEditor::make('keterangan')
                    ->nullable()
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                    ]),
                Select::make('tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->options(
                        TahunFiskal::pluck('nama_tahun_fiskal', 'id')->toArray()
                    )
                    ->required()
                    ->disabled() // Ini akan membuat field menjadi readonly
                    ->default(function () {
                        // Cari record TahunFiskal yang is_active = true
                        $activeTahunFiskal = TahunFiskal::where('is_active', true)->first();

                        // Jika ditemukan, gunakan ID-nya sebagai nilai default
                        if ($activeTahunFiskal) {
                            return $activeTahunFiskal->id;
                        }

                        // Jika tidak ada yang aktif, Anda bisa mengembalikan null atau ID default lainnya
                        // Misalnya, jika Anda ingin default ke tahun fiskal saat ini jika tidak ada yang aktif:
                        // $currentYear = date('Y');
                        // $currentTahunFiskal = TahunFiskal::where('tahun_fiskal', $currentYear)->first();
                        // return $currentTahunFiskal ? $currentTahunFiskal->id : null;
            
                        return null; // Mengembalikan null jika tidak ada tahun fiskal aktif
                    })
                    ->validationMessages([
                        'required' => 'Tahun Fiskal belum diaktifkan oleh admin'
                    ]),
                Forms\Components\Hidden::make('tahun_fiskal')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Strategi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->formatStateUsing(fn(?string $state): string => strip_tags($state ?? ''))
                    ->limit(50),
                Tables\Columns\TextColumn::make('dariTahunFiskal.nama_tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
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
            ])
            ->defaultSort('tahun_fiskal', 'desc');;
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
            'index' => Pages\ListStrategis::route('/'),
            // 'create' => Pages\CreateStrategi::route('/create'),
            // 'edit' => Pages\EditStrategi::route('/{record}/edit'),
        ];
    }
}
