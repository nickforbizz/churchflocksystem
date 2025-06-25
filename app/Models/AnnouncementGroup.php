<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AnnouncementGroup
 * 
 * @property int $id
 * @property int $announcement_id
 * @property int $group_id
 * @property int|null $active
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Announcement $announcement
 * @property User $user
 * @property Group $group
 *
 * @package App\Models
 */
class AnnouncementGroup extends Model
{
	use SoftDeletes;
	protected $table = 'announcement_group';

	protected $casts = [
		'announcement_id' => 'int',
		'group_id' => 'int',
		'active' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'announcement_id',
		'group_id',
		'active',
		'created_by'
	];

	public function announcement()
	{
		return $this->belongsTo(Announcement::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function group()
	{
		return $this->belongsTo(Group::class);
	}
}
