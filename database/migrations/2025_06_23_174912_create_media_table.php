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
        Schema::create('media', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('title', 55)->nullable();
            $table->string('type')->nullable()->comment('audio, video, PDF');
            $table->text('url')->nullable();
            $table->integer('active')->nullable();
            $table->unsignedBigInteger('created_by')->index('mediafk_created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
