<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BidangResource\Pages;
use App\Filament\Resources\BidangResource\RelationManagers;
use App\Models\Bidang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

use App\Filament\Traits\HasResourcePermissions;

class BidangResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'data_induk';
    protected static ?string $model = Bidang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Data Induk';
    protected static ?string $navigationLabel = 'Pilar';
    protected static ?string $pluralModelLabel = 'Pilar';
    protected static ?string $modelLabel = 'Data';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sdgTujuans')
                    ->multiple() // Mengizinkan pilihan ganda
                    ->relationship(name: 'sdgTujuans', titleAttribute: 'tujuan') // (nama relasi, kolom yang ditampilkan)
                    ->searchable()
                    ->preload() // Langsung load pilihan saat form dibuka
                    ->label('SDGs Terkait')
                    ->columnSpanFull(),
                TextInput::make('kode_bidang')
                    ->label('Kode Pilar')
                    ->required()
                    ->maxLength(255)
                    ->unique(Bidang::class, 'kode_bidang', ignoreRecord: true),
                TextInput::make('nama_bidang')
                    ->label('Nama Pilar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('keterangan')
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_bidang')
                    ->label('Kode Pilar')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_bidang')
                    ->label('Nama Pilar')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->formatStateUsing(fn(?string $state): string => strip_tags($state ?? ''))
                    ->limit(50)
                    ->searchable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBidangs::route('/'),
            // 'create' => Pages\CreateBidang::route('/create'),
            // 'edit' => Pages\EditBidang::route('/{record}/edit'),
        ];
    }
}
