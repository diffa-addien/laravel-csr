<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari user yang ingin diberi role
        $user = User::where('email', 'tester@admin.com')->first();

        // Jika user ditemukan, berikan role
        if ($user) {
            $user->assignRole('admin_aplikasi');
        }
    }
}