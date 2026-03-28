<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tblprojects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('tbladmins')->cascadeOnDelete();
            $table->string('title');
            $table->string('img')->nullable();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->unsignedInteger('view')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->index('admin_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblprojects');
    }
};
