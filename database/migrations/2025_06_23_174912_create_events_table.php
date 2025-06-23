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
        Schema::create('events', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('title', 100)->nullable();
            $table->string('description')->nullable();
            $table->date('event_date')->nullable();
            $table->integer('active')->nullable();
            $table->unsignedBigInteger('created_by')->index('eventsfk_created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
