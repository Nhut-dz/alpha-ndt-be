<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbladmins', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->after('role_id')->comment('1 = active, 0 = blocked');
        });
    }

    public function down(): void
    {
        Schema::table('tbladmins', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
