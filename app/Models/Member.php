<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Mix;
use Illuminate\Notifications\Notifiable;

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
	use SoftDeletes, Notifiable;
	protected $table = 'members';

	protected $casts = [
		'birth_date' => 'date',
		'join_date' => 'date',
		'group_id' => 'int',
		'homecell_id' => 'int',
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

	public function donations()
	{
		return $this->hasMany(Donation::class);
	}

	public function event_attendances()
	{
		return $this->hasMany(EventAttendance::class);
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

	

	public function ministries()
	{
		return $this->belongsToMany(Ministry::class, 'member_has_ministries');
	}
}
