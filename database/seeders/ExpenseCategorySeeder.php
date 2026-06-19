<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Rent',
            'Electricity',
            'Water',
            'Fuel',
            'Salaries',
            'Repairs',
            'Miscellaneous',
        ];

        foreach ($categories as $name) {
            ExpenseCategory::firstOrCreate(['name' => $name]);
        }
    }
}
