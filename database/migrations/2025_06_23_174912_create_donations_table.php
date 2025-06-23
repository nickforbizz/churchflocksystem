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
        Schema::create('donations', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('member_id')->nullable()->index('donationsfk_member_id');
            $table->float('amount', null, 0)->nullable();
            $table->string('method', 55)->nullable();
            $table->string('purpose', 55)->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('created_by')->index('donationsfk_created_by');
            $table->softDeletes();
            $table->timestamps();
            $table->integer('active')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
