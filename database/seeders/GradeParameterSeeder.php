<?php

namespace Database\Seeders;

use App\Models\GradeParameter;
use Illuminate\Database\Seeder;

class GradeParameterSeeder extends Seeder
{
    public function run(): void
    {
        $grades = [
            ['min_score' => 4.50, 'max_score' => 5.00, 'grade' => 'A', 'label' => 'Sangat Baik', 'color' => '#10B981'],
            ['min_score' => 3.50, 'max_score' => 4.49, 'grade' => 'B', 'label' => 'Baik', 'color' => '#3B82F6'],
            ['min_score' => 2.50, 'max_score' => 3.49, 'grade' => 'C', 'label' => 'Cukup', 'color' => '#F59E0B'],
            ['min_score' => 1.50, 'max_score' => 2.49, 'grade' => 'D', 'label' => 'Kurang', 'color' => '#F97316'],
            ['min_score' => 1.00, 'max_score' => 1.49, 'grade' => 'E', 'label' => 'Sangat Kurang', 'color' => '#EF4444'],
        ];
        
        foreach ($grades as $grade) {
            GradeParameter::updateOrCreate(
                ['grade' => $grade['grade']],
                $grade
            );
        }
        
        echo "✅ Grade parameters seeded!\n";
    }
}
