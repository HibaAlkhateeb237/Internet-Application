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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('government_agency_id')->constrained()->cascadeOnDelete();
            $table->string('reference_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->enum('status',['new','in_progress','resolved','rejected'])->default('new');

            // لمنع التعديل المتزامن
            $table->foreignId('locked_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('locked_at')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
