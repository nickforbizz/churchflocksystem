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
        Schema::table('donations', function (Blueprint $table) {
            $table->foreign(['created_by'], 'donationsFK_created_by')->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['member_id'], 'donationsFK_member_id')->references(['id'])->on('members')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign('donationsFK_created_by');
            $table->dropForeign('donationsFK_member_id');
        });
    }
};
