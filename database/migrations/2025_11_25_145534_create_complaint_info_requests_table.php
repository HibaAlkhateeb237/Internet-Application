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
        Schema::create('complaint_info_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('complaint_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();

            $table->text('request_text');   // نص المعلومات المطلوبة
            $table->text('citizen_response')->nullable(); // رد المواطن
            $table->string('attachment')->nullable(); // ملف يرفعه المواطن (اختياري)

            $table->boolean('is_answered')->default(false);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_info_requests');
    }
};
