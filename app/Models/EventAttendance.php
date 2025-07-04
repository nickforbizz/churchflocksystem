<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EventAttendance
 * 
 * @property int $id
 * @property int|null $member_id
 * @property int|null $event_id
 * @property Carbon|null $attendance_date
 * @property Carbon|null $attendance_time
 * @property string|null $notes
 * @property string|null $attendance_type
 * @property string|null $status
 * @property int|null $active
 * @property int $created_by
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Event|null $event
 * @property Member|null $member
 *
 * @package App\Models
 */
class EventAttendance extends Model
{
	use SoftDeletes;
	protected $table = 'event_attendance';

	protected $casts = [
		'member_id' => 'int',
		'event_id' => 'int',
		'attendance_date' => 'datetime',
		'attendance_time' => 'datetime',
		'active' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'member_id',
		'event_id',
		'attendance_date',
		'attendance_time',
		'notes',
		'attendance_type',
		'status',
		'active',
		'created_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function event()
	{
		return $this->belongsTo(Event::class);
	}

	public function member()
	{
		return $this->belongsTo(Member::class);
	}
}
