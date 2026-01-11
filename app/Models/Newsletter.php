<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Newsletter
 * 
 * @property int $id
 * @property string $email
 * @property string|null $name
 * @property Carbon|null $subscribed_at
 * @property Carbon|null $unsubscribed_at
 * @property string|null $status
 * @property array|null $preferences
 * @property string|null $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Newsletter extends Model
{
	protected $table = 'newsletters';

	protected $casts = [
		'subscribed_at' => 'datetime',
		'unsubscribed_at' => 'datetime',
		'preferences' => 'json'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'email',
		'name',
		'subscribed_at',
		'unsubscribed_at',
		'status',
		'preferences',
		'token'
	];
}
