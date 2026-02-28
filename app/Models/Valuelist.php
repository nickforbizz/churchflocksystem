<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Valuelist
 * 
 * @property int $id
 * @property string|null $type
 * @property string|null $value
 * @property int|null $index
 * @property int|null $active
 * @property int|null $status
 * @property int|null $created_by
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class Valuelist extends Model
{
	use SoftDeletes;
	protected $table = 'valuelist';

	protected $casts = [
		'index' => 'int',
		'active' => 'int',
		'status' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'type',
		'value',
		'index',
		'active',
		'status',
		'created_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}
}
