<?php

namespace App\Jobs;

use App\Models\Announcement;
use App\Models\Group;
use App\Notifications\AnnouncementNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAnnouncementToGroupMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $announcement;
    protected $groupIds;
    protected $message;
    protected $sendVia;

    /**
     * Create a new job instance.
     *
     * @param Announcement $announcement
     * @param array $groupIds
     * @param array $sendVia
     * @return void
     */
    public function __construct(Announcement $announcement, array $groupIds, array $sendVia, string $message = '')
    {
        $this->announcement = $announcement;
        $this->groupIds = $groupIds;
        $this->sendVia = $sendVia;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $groups = Group::whereIn('id', $this->groupIds)->with('members')->get(); // Eager load members and their associated users

        foreach ($groups as $group) {
            foreach ($group->members as $member) {
                

                if ($member && $member->email && in_array('email', $this->sendVia)) {
                    $member->notify(new AnnouncementNotification($this->announcement, 'email', $this->message)); // This will only work if 'email' channel is configured
                }
                // For SMS, ensure the Member model has a 'phone' field and routeNotificationForTwilio method.
                if ($member && $member->phone && in_array('sms', $this->sendVia)) {
                    $member->notify(new AnnouncementNotification($this->announcement, 'sms', $this->message));  // This will only work if 'sms' channel is configured
                }
            }
        }
    }
}