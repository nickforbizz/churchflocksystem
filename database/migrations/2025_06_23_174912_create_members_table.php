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
        Schema::create('members', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('full_name', 100)->nullable();
            $table->string('phone', 55)->nullable();
            $table->string('email', 55)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('marital_status', 10)->nullable();
            $table->date('join_date')->nullable();
            $table->bigInteger('group_id')->index('membersfk_groupid');
            $table->unsignedBigInteger('created_by')->index('membersfk_created_by');
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
        Schema::dropIfExists('members');
    }
};
