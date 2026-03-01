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
        Schema::create('valuelist', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('type', 100)->nullable();
            $table->string('value', 255)->nullable();
            $table->integer('index')->nullable();
            $table->integer('active')->nullable()->default(1);
            $table->integer('status')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('valuelistfk_created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valuelist');
    }
};
