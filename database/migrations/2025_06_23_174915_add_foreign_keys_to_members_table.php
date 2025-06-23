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
        Schema::table('members', function (Blueprint $table) {
            $table->foreign(['created_by'], 'membersFK_created_by')->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['group_id'], 'membersFk_groupId')->references(['id'])->on('groups')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign('membersFK_created_by');
            $table->dropForeign('membersFk_groupId');
        });
    }
};
