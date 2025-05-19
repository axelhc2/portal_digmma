<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EnterpriseCategory;

class EnterpriseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Marketing Digital',
            'Développement Web',
            'Design Graphique',
            'Consulting',
            'Formation',
            'E-commerce',
            'Services Financiers',
            'Immobilier',
            'Santé',
            'Transport'
        ];

        foreach ($categories as $category) {
            EnterpriseCategory::create([
                'name' => $category
            ]);
        }
    }
} 