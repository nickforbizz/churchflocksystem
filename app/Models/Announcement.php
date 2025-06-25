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
 * Class Announcement
 * 
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $body
 * @property int|null $is_public
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property int|null $active
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property User $user
 * @property Collection|Group[] $groups
 *
 * @package App\Models
 */
class Announcement extends Model
{
	use SoftDeletes;
	protected $table = 'announcements';

	protected $casts = [
		'is_public' => 'int',
		'starts_at' => 'datetime',
		'ends_at' => 'datetime',
		'active' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'title',
		'description',
		'body',
		'is_public',
		'starts_at',
		'ends_at',
		'active',
		'created_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function groups()
	{
		return $this->belongsToMany(Group::class)
					->withPivot('id', 'active', 'created_by', 'deleted_at')
					->withTimestamps();
	}
}
