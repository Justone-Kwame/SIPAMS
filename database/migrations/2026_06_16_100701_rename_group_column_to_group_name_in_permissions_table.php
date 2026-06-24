<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('permissions', 'group') && !Schema::hasColumn('permissions', 'group_name')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->renameColumn('group', 'group_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('permissions', 'group_name') && !Schema::hasColumn('permissions', 'group')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->renameColumn('group_name', 'group');
            });
        }
    }
};
