<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixPermissionColumnNameSeeder extends Seeder
{
    public function run(): void
    {
        try {
            DB::statement('ALTER TABLE permissions CHANGE COLUMN `group` group_name VARCHAR(255) NOT NULL');
            $this->command->info('Column renamed successfully!');
        } catch (\Exception $e) {
            // Check if column already renamed
            if (strpos($e->getMessage(), 'Unknown column \'group\'') === false) {
                throw $e;
            }
            $this->command->info('Column already renamed!');
        }
    }
}
