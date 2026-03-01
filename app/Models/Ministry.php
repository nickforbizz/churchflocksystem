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

	/**
	 * Get members via the pivot table.
	 */
	public function members()
	{
		return $this->belongsToMany(
			Member::class,
			'member_has_ministries',
			'ministry_id',
			'member_id'
		);
	}

	/**
	 * Get member_has_ministries records.
	 */
	public function memberHasMinistries()
	{
		return $this->hasMany(MemberHasMinistry::class, 'ministry_id');
	}
}
