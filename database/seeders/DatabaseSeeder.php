<?php

namespace Database\Seeders;

use App\Models\Cabinet;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator dengan akses penuh'],
            ['name' => 'bph', 'description' => 'Badan Pengurus Harian'],
            ['name' => 'kabinet', 'description' => 'Kepala Departemen'],
            ['name' => 'staff', 'description' => 'Anggota Staff'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }

        // Create cabinet
        $cabinet = Cabinet::firstOrCreate(
            ['year' => '2025/2026'],
            [
                'name' => 'Kabinet Harmoni',
                'status' => 'active',
            ]
        );

        // Create departments
        $departments = [
            ['name' => 'PSDM', 'description' => 'Pengembangan Sumber Daya Manusia'],
            ['name' => 'Medinfo', 'description' => 'Media dan Informasi'],
            ['name' => 'Humas', 'description' => 'Hubungan Masyarakat'],
            ['name' => 'Ristek', 'description' => 'Riset dan Teknologi'],
            ['name' => 'Akademik', 'description' => 'Bidang Akademik'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['name' => $dept['name']],
                array_merge($dept, ['cabinet_id' => $cabinet->id, 'status' => 'active'])
            );
        }

        // Create sample users
        $adminRole = Role::where('name', 'admin')->first();
        $bphRole = Role::where('name', 'bph')->first();
        $kabinetRole = Role::where('name', 'kabinet')->first();
        $staffRole = Role::where('name', 'staff')->first();
        $psdm = Department::where('name', 'PSDM')->first();
        $medinfo = Department::where('name', 'Medinfo')->first();

        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@savana.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'department_id' => null,
                'status' => 'active',
            ]
        );

        // BPH user
        User::firstOrCreate(
            ['email' => 'bph@savana.test'],
            [
                'name' => 'Ketua Umum',
                'password' => Hash::make('password'),
                'role_id' => $bphRole->id,
                'department_id' => null,
                'status' => 'active',
            ]
        );

        // Kabinet users
        User::firstOrCreate(
            ['email' => 'kabinet.psdm@savana.test'],
            [
                'name' => 'Kepala PSDM',
                'password' => Hash::make('password'),
                'role_id' => $kabinetRole->id,
                'department_id' => $psdm->id,
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kabinet.medinfo@savana.test'],
            [
                'name' => 'Kepala Medinfo',
                'password' => Hash::make('password'),
                'role_id' => $kabinetRole->id,
                'department_id' => $medinfo->id,
                'status' => 'active',
            ]
        );

        // Staff users
        User::firstOrCreate(
            ['email' => 'staff1@savana.test'],
            [
                'name' => 'Staff PSDM 1',
                'password' => Hash::make('password'),
                'role_id' => $staffRole->id,
                'department_id' => $psdm->id,
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff2@savana.test'],
            [
                'name' => 'Staff Medinfo 1',
                'password' => Hash::make('password'),
                'role_id' => $staffRole->id,
                'department_id' => $medinfo->id,
                'status' => 'active',
            ]
        );

        // Seed grade parameters
        $this->call(GradeParameterSeeder::class);
        
        $this->command->info('✅ Seeding completed!');
        $this->command->info('📧 Admin: admin@savana.test / password');
        $this->command->info('📧 BPH: bph@savana.test / password');
        $this->command->info('📧 Kabinet: kabinet.psdm@savana.test / password');
        $this->command->info('📧 Staff: staff1@savana.test / password');
    }
}
