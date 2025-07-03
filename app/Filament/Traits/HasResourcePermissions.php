<?php

namespace App\Filament\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasResourcePermissions
{
    /**
     * Mendapatkan prefix izin yang final.
     * Metode ini akan mencari properti '$permissionPrefix' di Resource.
     * Jika tidak ditemukan, ia akan membuat prefix secara otomatis dari nama Model.
     */
    public static function getPermissionPrefix(): string
    {
        // Cek apakah properti $permissionPrefix didefinisikan di dalam resource
        if (property_exists(static::class, 'permissionPrefix')) {
            return static::$permissionPrefix;
        }

        // Jika tidak, jalankan logika otomatis
        return Str::plural(Str::snake(class_basename(static::getModel())));
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_' . static::getPermissionPrefix());
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_' . static::getPermissionPrefix());
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('edit_' . static::getPermissionPrefix());
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete_' . static::getPermissionPrefix());
    }
}