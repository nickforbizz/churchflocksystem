<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Child
 * 
 * @property int $id
 * @property int $member_id
 * @property string|null $name
 * @property int|null $active
 * @property int $created_by
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Member $member
 * @property User $user
 *
 * @package App\Models
 */
class Child extends Model
{
	use SoftDeletes;
	protected $table = 'children';

	protected $casts = [
		'member_id' => 'int',
		'active' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'member_id',
		'name',
		'active',
		'created_by'
	];

	public function member()
	{
		return $this->belongsTo(Member::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}
}
