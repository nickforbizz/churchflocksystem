<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Donation
 * 
 * @property int $id
 * @property int|null $member_id
 * @property float|null $amount
 * @property string|null $method
 * @property string|null $purpose
 * @property Carbon|null $date
 * @property int $created_by
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $active
 * 
 * @property User $user
 * @property Member|null $member
 *
 * @package App\Models
 */
class Donation extends Model
{
	use SoftDeletes;
	protected $table = 'donations';

	protected $casts = [
		'member_id' => 'int',
		'amount' => 'float',
		'date' => 'date',
		'created_by' => 'int',
		'active' => 'int'
	];

	protected $fillable = [
		'member_id',
		'amount',
		'method',
		'purpose',
		'date',
		'created_by',
		'active'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function member()
	{
		return $this->belongsTo(Member::class);
	}
}
