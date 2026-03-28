<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblprojects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('client')->nullable();
            $table->string('industry')->nullable();
            $table->string('location')->nullable();
            $table->string('year', 10)->nullable();
            $table->string('duration')->nullable();
            $table->string('tag')->nullable();
            $table->string('img')->nullable();
            $table->json('highlights')->nullable();
            $table->json('methods')->nullable();
            $table->json('standards')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0: draft, 1: published');
            $table->integer('sort_order')->default(0);
            $table->foreignId('admin_id')->constrained('tbladmins')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblprojects');
    }
};
