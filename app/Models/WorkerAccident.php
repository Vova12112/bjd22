<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WorkerAccident
 * @property int    worker_id
 * @property int    accident_type_id
 * @property Carbon accident_at
 * @property int    hours_after_start_working
 * @package App\Models
 */
class WorkerAccident extends AModel
{
	/*** @var string[] */
	protected $dates = ['accident_at', 'created_at', 'updated_at', 'deleted_at'];

	/*** @return BelongsTo */
	public function accidentType(): BelongsTo
	{
		return $this->belongsTo(AccidentType::class);
	}

	/*** @return BelongsTo */
	public function worker(): BelongsTo
	{
		return $this->belongsTo(Worker::class);
	}

	/*** @return Carbon */
	public function getAccidentAt(): Carbon
	{
		return $this->accident_at;
	}

	/*** @param Carbon $accident_at */
	public function setAccidentAt(Carbon $accident_at): void
	{
		$this->accident_at = $accident_at;
	}

	/*** @return int */
	public function getHoursAfterStartWorking(): int
	{
		return $this->hours_after_start_working;
	}

	/*** @param int $hours_after_start_working */
	public function setHoursAfterStartWorking(int $hours_after_start_working): void
	{
		$this->hours_after_start_working = $hours_after_start_working;
	}

}
