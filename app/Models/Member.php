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
 * @property int|null $member_number
 * @property string|null $full_name
 * @property string|null $phone
 * @property string|null $email
 * @property Carbon|null $birth_date
 * @property string|null $marital_status
 * @property string|null $spouse
 * @property string|null $spouse_number
 * @property string|null $next_of_kin
 * @property string|null $next_of_kin_number
 * @property Carbon|null $join_date
 * @property Carbon|null $official_join_date
 * @property string|null $residency
 * @property string|null $postal_address
 * @property string|null $occupation
 * @property int|null $born_again
 * @property Carbon|null $spirit_filled_when
 * @property Carbon|null $water_immersed_when
 * @property string|null $from_church
 * @property string|null $from_church_branch
 * @property string|null $from_church_pastor
 * @property string|null $from_church_pastor_number
 * @property int $group_id
 * @property int|null $homecell_id
 * @property int $created_by
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $active
 * 
 * @property User $user
 * @property Group $group
 * @property Homecell|null $homecell
 * @property Collection|Child[] $children
 * @property Collection|Donation[] $donations
 * @property Collection|EventAttendance[] $event_attendances
 * @property Collection|MemberHasMinistry[] $member_has_ministries
 *
 * @package App\Models
 */
class Member extends Model
{
	use SoftDeletes;
	protected $table = 'members';

	protected $casts = [
		'member_number' => 'int',
		'birth_date' => 'datetime',
		'join_date' => 'datetime',
		'official_join_date' => 'datetime',
		'born_again' => 'int',
		'spirit_filled_when' => 'datetime',
		'water_immersed_when' => 'datetime',
		'group_id' => 'int',
		'homecell_id' => 'int',
		'created_by' => 'int',
		'active' => 'int'
	];

	protected $fillable = [
		'member_number',
		'full_name',
		'phone',
		'email',
		'birth_date',
		'marital_status',
		'gender',
		'spouse',
		'spouse_number',
		'next_of_kin',
		'next_of_kin_number',
		'join_date',
		'official_join_date',
		'residency',
		'postal_address',
		'occupation',
		'born_again',
		'spirit_filled_when',
		'water_immersed_when',
		'from_church',
		'from_church_branch',
		'from_church_pastor',
		'from_church_pastor_number',
		'group_id',
		'homecell_id',
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

	public function homecell()
	{
		return $this->belongsTo(Homecell::class);
	}

	public function children()
	{
		return $this->hasMany(Child::class);
	}

	public function donations()
	{
		return $this->hasMany(Donation::class);
	}

	public function event_attendances()
	{
		return $this->hasMany(EventAttendance::class);
	}

	public function ministries()
	{
		return $this->belongsToMany(
			Ministry::class,
			'member_has_ministries',
			'member_id',
			'ministry_id'
		);
	}

	public function member_has_ministries()
	{
		return $this->hasMany(MemberHasMinistry::class);
	}

	/**
	 * Route notifications for the Twilio channel.
	 *
	 * @param  \Illuminate\Notifications\Notification  $notification
	 * @return string
	 */
	public function routeNotificationForTwilio($notification)
	{
		return $this->phone; // Assuming 'phone' column exists for SMS
	}
}
