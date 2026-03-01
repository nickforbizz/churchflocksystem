<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

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

	/**
	 * Scope to filter by type
	 */
	public function scopeOfType($query, string $type)
	{
		return $query->where('type', $type)->where('active', 1);
	}

	/**
	 * Get all values for a given type
	 * 
	 * @param string $type The type to filter by (e.g., 'gender', 'marital_status')
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public static function getByType(string $type)
	{
		return Cache::remember("valuelist_{$type}", 3600, function () use ($type) {
			return static::ofType($type)->orderBy('index')->get();
		});
	}

	/**
	 * Get values as dropdown array [value => value] for forms
	 * 
	 * @param string $type The type to filter by
	 * @param bool $useIndexAsKey If true, uses index as key; otherwise uses value
	 * @return array
	 */
	public static function getDropdown(string $type, bool $useIndexAsKey = false): array
	{
		$items = static::getByType($type);
		
		if ($useIndexAsKey) {
			return $items->pluck('value', 'index')->toArray();
		}
		
		return $items->pluck('value', 'value')->toArray();
	}

	/**
	 * Get dropdown as array with lowercase keys (useful for storing in DB)
	 * 
	 * @param string $type The type to filter by
	 * @return array [lowercase_value => Display Value]
	 */
	public static function getDropdownLower(string $type, bool $useIndexAsKey = false): array
	{
		$items = static::getByType($type);
		$result = [];
		
		foreach ($items as $item) {
			if ($useIndexAsKey) {
				$result[$item->index] = $item->value;
			} else {
				$result[strtolower($item->value)] = $item->value;
			}
		}
		
		return $result;
	}

	/**
	 * Get all distinct types
	 * 
	 * @return \Illuminate\Support\Collection
	 */
	public static function getTypes()
	{
		return Cache::remember('valuelist_types', 3600, function () {
			return static::select('type')
				->distinct()
				->orderBy('type')
				->pluck('type');
		});
	}

	/**
	 * Clear cache for a specific type or all valuelist caches
	 * 
	 * @param string|null $type
	 */
	public static function clearCache(?string $type = null): void
	{
		if ($type) {
			Cache::forget("valuelist_{$type}");
		} else {
			// Clear all known types
			$types = static::select('type')->distinct()->pluck('type');
			foreach ($types as $t) {
				Cache::forget("valuelist_{$t}");
			}
			Cache::forget('valuelist_types');
		}
	}

	/**
	 * Boot method to clear cache on model changes
	 */
	protected static function boot()
	{
		parent::boot();

		static::saved(function ($model) {
			static::clearCache($model->type);
		});

		static::deleted(function ($model) {
			static::clearCache($model->type);
		});
	}
}
