<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Param
 * 
 * @property int $id
 * @property string|null $data_type
 * @property string|null $group
 * @property string|null $key
 * @property string|null $value
 * @property bool|null $is_public
 * @property string|null $description
 * @property int|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class Param extends Model
{
	use SoftDeletes;
	protected $table = 'params';

	protected $casts = [
		'is_public' => 'bool',
		'created_by' => 'int'
	];

	protected $fillable = [
		'data_type',
		'group',
		'key',
		'value',
		'is_public',
		'description',
		'created_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}
}
