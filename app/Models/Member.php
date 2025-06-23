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
 * Class Member
 * 
 * @property int $id
 * @property string|null $full_name
 * @property string|null $phone
 * @property string|null $email
 * @property Carbon|null $birth_date
 * @property string|null $marital_status
 * @property Carbon|null $join_date
 * @property int $group_id
 * @property int $created_by
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $active
 * 
 * @property User $user
 * @property Group $group
 * @property Collection|Donation[] $donations
 * @property Collection|EventAttendance[] $event_attendances
 *
 * @package App\Models
 */
class Member extends Model
{
	use SoftDeletes;
	protected $table = 'members';

	protected $casts = [
		'birth_date' => 'date',
		'join_date' => 'date',
		'group_id' => 'int',
		'created_by' => 'int',
		'active' => 'int'
	];

	protected $fillable = [
		'full_name',
		'phone',
		'email',
		'birth_date',
		'marital_status',
		'join_date',
		'group_id',
		'created_by',
		'active'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function group()
	{
		return $this->belongsTo(Group::class);
	}

	public function donations()
	{
		return $this->hasMany(Donation::class);
	}

	public function event_attendances()
	{
		return $this->hasMany(EventAttendance::class);
	}
}
