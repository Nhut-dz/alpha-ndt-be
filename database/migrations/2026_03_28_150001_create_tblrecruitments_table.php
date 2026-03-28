<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblrecruitments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('position')->nullable()->comment('Vị trí tuyển dụng');
            $table->string('department')->nullable()->comment('Phòng ban');
            $table->string('location')->nullable()->comment('Địa điểm làm việc');
            $table->string('employment_type')->nullable()->comment('full-time, part-time, contract, intern');
            $table->integer('quantity')->default(1)->comment('Số lượng tuyển');
            $table->string('salary_range')->nullable()->comment('Mức lương');
            $table->text('requirements')->nullable()->comment('Yêu cầu');
            $table->text('benefits')->nullable()->comment('Quyền lợi');
            $table->date('deadline')->nullable()->comment('Hạn nộp hồ sơ');
            $table->string('contact_email')->nullable();
            $table->string('img')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0: draft, 1: published, 2: closed');
            $table->foreignId('admin_id')->constrained('tbladmins')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblrecruitments');
    }
};
