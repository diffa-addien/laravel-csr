<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Spatie\Permission\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use App\Filament\Traits\HasResourcePermissions;

class PermissionResource extends Resource
{
    use HasResourcePermissions;
    protected static ?string $permissionPrefix = 'users';
    protected static ?string $model = Permission::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?string $navigationLabel = 'Izin Akses';
    protected static ?int $navigationSort = 3;

    public static function getPluralModelLabel(): string
    {
        return 'Izin Akses';
    }
    
    // Kita tidak menggunakan Form standar karena pembuatan permission akan dilakukan lewat custom action
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Permission Name')
                ->required(),
            Forms\Components\Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Izin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role Terhubung')
                    ->badge()
                    ->separator(', '),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                // Filter ini akan sangat membantu!
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
            ->headerActions([
                // Ini adalah action utama untuk membuat permission baru
                Tables\Actions\Action::make('createPermission')
                    ->label('Buat Izin Akses Baru')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Forms\Components\TextInput::make('resource')
                            ->label('Nama Resource (Model)')
                            ->helperText('Contoh: User, VendorResource, Role, dsb.')
                            ->required(),
                        Forms\Components\CheckboxList::make('actions')
                            ->label('Aksi yang Diizinkan')
                            ->options([
                                'view'   => 'Lihat (Read)',
                                'create' => 'Buat (Create)',
                                'edit'   => 'Ubah (Update)',
                                'delete' => 'Hapus (Delete)',
                            ])
                            ->columns(2)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $permissions = [];
                        // Membuat slug dari nama resource, contoh: 'User' -> 'users'
                        $resourceSlug = Str::snake($data['resource']);

                        foreach ($data['actions'] as $action) {
                            $permissionName = $action . '_' . $resourceSlug;
                            
                            // Cek jika permission sudah ada, jika belum maka buat baru
                            $permission = Permission::firstOrCreate(['name' => $permissionName]);
                            $permissions[] = $permission;
                        }
                        
                        // Opsi: Anda bisa tambahkan notifikasi sukses di sini
                    })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            // Halaman create dan edit bawaan tidak kita perlukan lagi untuk pembuatan
            // 'create' => Pages\CreatePermission::route('/create'), 
            // 'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}