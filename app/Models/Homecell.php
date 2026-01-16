<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Homecell
 * 
 * @property int $id
 * @property int $member_id
 * @property string|null $primary_cell
 * @property string|null $prayercell_leader
 * @property Carbon|null $date_joined
 * @property Carbon|null $date_officially_received
 * @property int|null $active
 * @property int $created_by
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Member $member
 *
 * @package App\Models
 */
class Homecell extends Model
{
	use SoftDeletes;
	protected $table = 'homecells';

	protected $casts = [
		'date_joined' => 'datetime',
		'date_officially_received' => 'datetime',
		'active' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'primary_cell',
		'prayercell_leader',
		'date_joined',
		'date_officially_received',
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
	
}
