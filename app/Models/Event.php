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
		'created_by' => 'int'
	];

	protected $fillable = [
		'title',
		'description',
		'event_date',
		'active',
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
}
