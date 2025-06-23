<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Medium
 * 
 * @property int $id
 * @property string|null $title
 * @property string|null $type
 * @property string|null $url
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
class Medium extends Model
{
	use SoftDeletes;
	protected $table = 'media';

	protected $casts = [
		'active' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'title',
		'type',
		'url',
		'active',
		'created_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}
}
