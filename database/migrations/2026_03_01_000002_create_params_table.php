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
        Schema::create('params', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('data_type', 50)->nullable();
            $table->string('group', 100)->nullable();
            $table->string('key', 100)->nullable();
            $table->text('value')->nullable();
            $table->boolean('is_public')->nullable()->default(true);
            $table->string('description', 500)->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('paramsfk_created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('params');
    }
};
