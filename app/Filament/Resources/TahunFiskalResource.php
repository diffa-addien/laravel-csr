<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahunFiskalResource\Pages;
use App\Filament\Resources\TahunFiskalResource\RelationManagers;
use App\Models\TahunFiskal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule; // Pastikan ini diimpor
use Filament\Forms\Get; // Tambahkan ini jika Anda ingin menggunakan Get/Set

use App\Filament\Traits\HasResourcePermissions;

class TahunFiskalResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = TahunFiskal::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $modelLabel = 'Data';
    protected static ?string $pluralModelLabel = 'Tahun Fiskal';
    protected static ?string $navigationLabel = 'Tahun Fiskal';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->required()
                    ->unique(
                        TahunFiskal::class, // Nama class model
                        'nama_tahun_fiskal',      // Nama kolom yang ingin di-unique-kan
                        fn(?Model $record): ?Model => $record, // Abaikan record saat ini saat edit
                    ),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(false)
                    ->rule(function () use ($form) {
                        return function ($attribute, $value, $fail) use ($form) {
                            if ($value === true) {
                                $existingActive = \DB::table('tahun_fiskals')
                                    ->where('is_active', true)
                                    ->where('id', '!=', $form->getRecord()?->id)
                                    ->exists();

                                if ($existingActive) {
                                    $fail('Hanya boleh ada satu (Tahun Fiskal) yang aktif');
                                }
                            }
                        };
                    }),
                DatePicker::make('tanggal_buka')
                    ->label('Tanggal Buka')
                    ->required(),
                DatePicker::make('tanggal_tutup')
                    ->label('Tanggal Tutup')
                    ->required(),
                TextInput::make('anggaran')
                    ->numeric()
                    ->prefix('Rp')
                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                    ->nullable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->sortable()
                    ->weight(FontWeight::Bold),
                TextColumn::make('periode')
                    ->label('Periode')
                    ->getStateUsing(function ($record) {
                        // Pastikan tanggal dalam instance Carbon dan bukan null
                        $tanggalBuka = $record->tanggal_buka
                            ? \Carbon\Carbon::parse($record->tanggal_buka)->translatedFormat('d F Y')
                            : '-';

                        $tanggalTutup = $record->tanggal_tutup
                            ? \Carbon\Carbon::parse($record->tanggal_tutup)->translatedFormat('d F Y')
                            : '-';

                        return "$tanggalBuka - $tanggalTutup";
                    }),
                TextColumn::make('anggaran')
                    ->label('Anggaran')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                BooleanColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListTahunFiskals::route('/'),
            // 'create' => Pages\CreateTahunFiskal::route('/create'),
            // 'edit' => Pages\EditTahunFiskal::route('/{record}/edit'),
        ];
    }
}
