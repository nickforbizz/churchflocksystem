<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ministry
 * 
 * @property int $id
 * @property string|null $name
 * @property int|null $active
 * @property int $created_by
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Ministry extends Model
{
	use SoftDeletes;
	protected $table = 'ministries';

	protected $casts = [
		'active' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'name',
		'active',
		'created_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function members()
	{
		return $this->hasMany(Member::class);
	}

	// get members count in this member_has_ministries table
	public function membersCount()
	{
		return $this->hasMany(MemberHasMinistry::class, 'ministry_id')->count();
	}
}
