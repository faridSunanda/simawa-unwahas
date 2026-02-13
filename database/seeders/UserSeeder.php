<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Superadmin
        $superadminRole = Role::where('name', 'superadmin')->first();
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@unwahas.ac.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $superadminRole->id,
                'is_active' => true,
            ]
        );
        Staff::updateOrCreate(
            ['user_id' => $superadmin->id],
            ['bagian' => 'IT', 'nip' => 'ADM001']
        );

        // Kemahasiswaan
        $kemahasiswaanRole = Role::where('name', 'kemahasiswaan')->first();
        $kemahasiswaan = User::updateOrCreate(
            ['email' => 'kemahasiswaan@unwahas.ac.id'],
            [
                'name' => 'Staff Kemahasiswaan',
                'password' => Hash::make('password'),
                'role_id' => $kemahasiswaanRole->id,
                'is_active' => true,
            ]
        );
        Staff::updateOrCreate(
            ['user_id' => $kemahasiswaan->id],
            ['bagian' => 'Kemahasiswaan', 'nip' => 'KMH001']
        );

        // Pimpinan
        $pimpinanRole = Role::where('name', 'pimpinan')->first();
        $pimpinan = User::updateOrCreate(
            ['email' => 'pimpinan@unwahas.ac.id'],
            [
                'name' => 'Rektor UNWAHAS',
                'password' => Hash::make('password'),
                'role_id' => $pimpinanRole->id,
                'is_active' => true,
            ]
        );
        Dosen::updateOrCreate(
            ['user_id' => $pimpinan->id],
            [
                'nip' => 'PMP001',
                'jabatan' => 'Rektor',
                'fakultas' => 'Universitas',
            ]
        );

        // Dekan
        $dekanRole = Role::where('name', 'dekan')->first();
        $dekan = User::updateOrCreate(
            ['email' => 'dekan@unwahas.ac.id'],
            [
                'name' => 'Dekan Fakultas Teknik',
                'password' => Hash::make('password'),
                'role_id' => $dekanRole->id,
                'is_active' => true,
            ]
        );
        Dosen::updateOrCreate(
            ['user_id' => $dekan->id],
            [
                'nip' => 'DKN001',
                'jabatan' => 'Dekan',
                'fakultas' => 'Fakultas Teknik',
            ]
        );

        // Kaprodi
        $kaprodiRole = Role::where('name', 'kaprodi')->first();
        $kaprodi = User::updateOrCreate(
            ['email' => 'kaprodi@unwahas.ac.id'],
            [
                'name' => 'Kaprodi Teknik Informatika',
                'password' => Hash::make('password'),
                'role_id' => $kaprodiRole->id,
                'is_active' => true,
            ]
        );
        Dosen::updateOrCreate(
            ['user_id' => $kaprodi->id],
            [
                'nip' => 'KPR001',
                'jabatan' => 'Kaprodi',
                'fakultas' => 'Fakultas Teknik',
                'prodi' => 'Teknik Informatika',
            ]
        );

        // Mahasiswa
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $mahasiswa = User::updateOrCreate(
            ['email' => 'mahasiswa@unwahas.ac.id'],
            [
                'name' => 'Ahmad Mahasiswa',
                'password' => Hash::make('password'),
                'role_id' => $mahasiswaRole->id,
                'is_active' => true,
            ]
        );
        Mahasiswa::updateOrCreate(
            ['user_id' => $mahasiswa->id],
            [
                'nim' => '2024001001',
                'fakultas' => 'Fakultas Teknik',
                'prodi' => 'Teknik Informatika',
                'angkatan' => '2024',
            ]
        );
    }
}
