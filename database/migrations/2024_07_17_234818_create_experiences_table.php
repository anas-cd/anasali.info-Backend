<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('role');
            $table->string('major');
            $table->text('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('employer_name');
            $table->string('employer_reference')->nullable();
            $table->string('employer_reference_contact')->nullable();
            $table->string('employer_image_path');
            $table->string('employer_website');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
