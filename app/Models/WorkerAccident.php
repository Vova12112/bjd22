<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WorkerAccident
 * @property int    worker_id
 * @property int    accident_type_id
 * @property Carbon sick_start_at
 * @property Carbon sick_end_at
 * @property Carbon accident_at
 * @property int    hours_after_start_working
 * @package App\Models
 * @method static where(string $string, string $string1, int $id)
 */
class WorkerAccident extends AModel
{
	/*** @var string[] */
	protected $dates = ['accident_at', 'sick_end_at', 'sick_start_at', 'created_at', 'updated_at', 'deleted_at'];

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

	/*** @return int */
	public function getWorkerId(): int
	{
		return $this->worker_id;
	}

	/*** @param int $worker_id */
	public function setWorkerId(int $worker_id): void
	{
		$this->worker_id = $worker_id;
	}

	/*** @return int */
	public function getAccidentTypeId(): int
	{
		return $this->accident_type_id;
	}

	/*** @param int $accident_type_id */
	public function setAccidentTypeId(int $accident_type_id): void
	{
		$this->accident_type_id = $accident_type_id;
	}

	/*** @return Carbon */
	public function getSickStartAt(): ?Carbon
	{
		return $this->sick_start_at;
	}

	/*** @param Carbon $sick_start_at */
	public function setSickStartAt(?Carbon $sick_start_at): void
	{
		$this->sick_start_at = $sick_start_at;
	}

	/*** @return Carbon */
	public function getSickEndAt(): ?Carbon
	{
		return $this->sick_end_at;
	}

	/***
	 * @param Carbon|null $sick_end_at
	 */
	public function setSickEndAt(?Carbon $sick_end_at): void
	{
		$this->sick_end_at = $sick_end_at;
	}

}
