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
        Schema::table('event_attendance', function (Blueprint $table) {
            $table->foreign(['created_by'], 'event_attendanceFK_created_by')->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['event_id'], 'event_attendanceFK_event_id')->references(['id'])->on('events')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['member_id'], 'event_attendanceFK_member_id')->references(['id'])->on('members')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_attendance', function (Blueprint $table) {
            $table->dropForeign('event_attendanceFK_created_by');
            $table->dropForeign('event_attendanceFK_event_id');
            $table->dropForeign('event_attendanceFK_member_id');
        });
    }
};
