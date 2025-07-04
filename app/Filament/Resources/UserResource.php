<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Traits\HasResourcePermissions;

class UserResource extends Resource
{
    // use HasResourcePermissions;
    // protected static ?string $permissionPrefix = 'users';
    protected static ?string $model = User::class;
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 1;

    public static function getPluralModelLabel(): string
    {
        return 'Pengguna';
    }

    // public static function canViewAny(): bool
    // {
    //     return auth()->user() ? auth()->user()->hasPermissionTo('view-users') : false;
    // }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required(),
            TextInput::make('email')
                ->email()
                ->unique(
                    User::class, // Nama class model
                    'email',     // Nama kolom yang ingin di-unique-kan
                    fn(?Model $record): ?Model => $record, // Abaikan record saat ini saat edit
                )
                ->required(),
            TextInput::make('password')
                ->password()
                ->label('Password')
                ->dehydrated(fn($state) => filled($state)) // hanya kirim ke DB jika tidak kosong
                ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                ->required(fn($context) => $context === 'create'),
            Select::make('roles')
                ->relationship('roles', 'name')
                ->label('Role')
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('roles.name')->label('Role')->badge()->separator(', '),
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
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

}
