<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MemberHasMinistry
 * 
 * @property int $member_id
 * @property int $ministry_id
 * 
 * @property Member $member
 * @property Ministry $ministry
 *
 * @package App\Models
 */
class MemberHasMinistry extends Model
{
	protected $table = 'member_has_ministries';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'member_id' => 'int',
		'ministry_id' => 'int'
	];

	public function member()
	{
		return $this->belongsTo(Member::class);
	}

	public function ministry()
	{
		return $this->belongsTo(Ministry::class);
	}
}
