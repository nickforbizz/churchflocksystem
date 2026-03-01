<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Event
 * 
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property Carbon|null $event_date
 * @property int|null $active
 * @property int $created_by
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Group $group
 * @property Collection|EventAttendance[] $event_attendances
 *
 * @package App\Models
 */
class Event extends Model
{
	use SoftDeletes;
	protected $table = 'events';

	protected $casts = [
		'event_date' => 'date',
		'active' => 'int',
		'group_id' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'title',
		'description',
		'event_date',
		'active',
		'location',
		'group_id',
		'created_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function event_attendances()
	{
		return $this->hasMany(EventAttendance::class);
	}

	public function group()
	{
		return $this->belongsTo(Group::class, 'group_id');
	}

	// get members in a group but incase of 'all' get all active members
	public function membersInGroupCount()
	{
		if ($this->group_id) {
			$group = Group::find($this->group_id);
			if ($group && strtolower($group->name) === 'all') {
				return Member::where('active', 1)->count();
			} elseif ($group) {
				return $group->members()->where('active', 1)->count();
			}
		}
		return 0;
	}

	// get total attendace in this event
	public function totalAttendance()
	{
		return $this->event_attendances()->count();
	}

	// percent attendance
	public function attendancePercentage(){
		$totalMembers = $this->membersInGroupCount();
		if ($totalMembers > 0) {
			// return $totalMembers;
			return round(($this->totalAttendance() / $totalMembers) * 100, 2) . '%';
		}
		return '0%';
	}
}
