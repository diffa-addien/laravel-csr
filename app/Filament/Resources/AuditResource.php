<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditResource\Pages;
use App\Filament\Resources\AuditResource\RelationManagers;
use App\Models\Audit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\KeyValueColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class AuditResource extends Resource
{
    protected static ?string $model = \OwenIt\Auditing\Models\Audit::class;

    // Atur ikon dan nama di sidebar
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Audit Trail';
    protected static ?string $modelLabel = 'Audit Trail';
    protected static ?string $pluralModelLabel = 'Audit Trail';

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Manajemen Pengguna'; // Opsional: untuk mengelompokkan menu

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user.name')->label('Pengguna')->disabled(),
                Forms\Components\TextInput::make('event')->label('Aksi')->disabled(),
                Forms\Components\TextInput::make('auditable_type')->label('Target Model')->disabled(),
                Forms\Components\TextInput::make('auditable_id')->label('Target ID')->disabled(),
                Forms\Components\KeyValue::make('old_values')->label('Data Lama')->disabled(),
                Forms\Components\KeyValue::make('new_values')->label('Data Baru')->disabled(),
                Forms\Components\DateTimePicker::make('created_at')->label('Waktu')->disabled(),
            ]);
    }

    // Di dalam kelas AuditResource

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable(),

                TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),

                // Kolom ini menampilkan model mana yang diubah (misal: "Bidang" atau "User")
                TextColumn::make('auditable_type')
                    ->label('Target Model')
                    ->searchable()
                    ->formatStateUsing(fn($state) => explode('\\', $state)[count(explode('\\', $state)) - 1]),

                // Kolom ini menampilkan ID dari record yang diubah
                TextColumn::make('auditable_id')
                    ->label('Target ID'),

                // TextColumn::make('old_values')
                //     ->label('Perubahan')
                //     // KODE BARU YANG SUDAH DIPERBAIKI
                //     ->formatStateUsing(fn($state) => (is_array($state) ? count($state) : 0) . ' field berubah'),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                // Anda bisa menambahkan filter di sini jika perlu
            ])
            ->actions([
                // Ganti tombol Edit dengan View
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]) // Hapus bulk actions
            ->defaultSort('created_at', 'desc');
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Audit')
                    ->columns(3)
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')->label('Dilakukan oleh'),
                        Infolists\Components\TextEntry::make('event')->label('Aksi')->badge(),
                        Infolists\Components\TextEntry::make('created_at')->label('Waktu')->dateTime(),
                    ]),
                Infolists\Components\Section::make('Detail Perubahan')
                    ->schema([
                        Infolists\Components\Section::make('')
                            ->columns(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('auditable_type')->label('Model')->badge(),
                                Infolists\Components\TextEntry::make('auditable_id')->label('Id')->badge(),
                            ]),

                        Infolists\Components\KeyValueEntry::make('old_values')
                            ->label('Data Sebelum Diubah')
                            ->visible(fn($state): bool => is_array($state) && count($state) > 0),

                        // KODE LAMA: Infolists\Components\KeyValueEntry::make('new_values')
                        // KODE BARU DENGAN ->visible():
                        Infolists\Components\KeyValueEntry::make('new_values')
                            ->label('Data Setelah Diubah')
                            ->visible(fn($state): bool => is_array($state) && count($state) > 0),
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
            'index' => Pages\ListAudits::route('/'),
            // 'create' => Pages\CreateAudit::route('/create'),
            // 'edit' => Pages\EditAudit::route('/{record}/edit'),
        ];
    }
}
