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

class TahunFiskalResource extends Resource
{
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
                TextInput::make('tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->required()
                    ->unique(
                        TahunFiskal::class, // Nama class model
                        'tahun_fiskal',      // Nama kolom yang ingin di-unique-kan
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

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahun_fiskal')
                    ->label('Tahun Fiskal')
                    ->weight(FontWeight::Bold),
                TextColumn::make('tanggal_buka')
                    ->label('Tanggal Buka')
                    ->date(),
                TextColumn::make('tanggal_tutup')
                    ->label('Tanggal Tutup')
                    ->date(),
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
