<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'superadmin',
                'display_name' => 'Super Admin',
                'description' => 'Administrator sistem dengan akses penuh',
            ],
            [
                'name' => 'kemahasiswaan',
                'display_name' => 'Kemahasiswaan',
                'description' => 'Staff bagian kemahasiswaan',
            ],
            [
                'name' => 'pimpinan',
                'display_name' => 'Pimpinan',
                'description' => 'Pimpinan universitas',
            ],
            [
                'name' => 'dekan',
                'display_name' => 'Dekan',
                'description' => 'Dekan fakultas',
            ],
            [
                'name' => 'kaprodi',
                'display_name' => 'Kaprodi',
                'description' => 'Ketua program studi',
            ],
            [
                'name' => 'mahasiswa',
                'display_name' => 'Mahasiswa',
                'description' => 'Mahasiswa aktif',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
