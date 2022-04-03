<?php

namespace App\Models\Repo;

use Carbon\Carbon;
use DB;

/**
 * Class WorkersModelRepo
 * @package App\Models\Repo
 */
class AnalizerRepo
{
	/**
	 * @param string|null $accident
	 * @param Carbon|null $dateStart
	 * @param Carbon|null $dateEnd
	 * @return int
	 */
	public function counter(?string $accident = NULL, ?Carbon $dateStart  = NULL, ?Carbon $dateEnd  = NULL): int
	{
		$query = DB::table('worker_accidents as t1')
			->join('accident_types as t2', 't1.accident_type_id', 't2.id');
		if ( ! empty($accident)) {
			$query->where('t2.name', 'like', $accident);
		}
		if ( ! empty($dateStart)) {
			$query->where('t1.accident_at', '>=',  $dateStart->toDateString());
		}
		if ( ! empty($dateEnd)) {
			$query->where('t1.accident_at', '<=', $dateEnd->toDateString());
		}
		return $query->count();
	}
	public function getAllAccidents(): array
	{
		return DB::table('accident_types')->pluck('name')->toArray();
	}

	public function getAllCategories(): array
	{
		return DB::table('profession_categories')->pluck('name')->toArray();
	}
	/**
	 * @param string|null $accident
	 * @param Carbon|null $dateStart
	 * @param Carbon|null $dateEnd
	 * @param int|null    $sex
	 * @param int|null    $fromOld
	 * @param int|null    $toOld
	 * @param int|null    $structure_segment
	 * @param int|null    $profession_id
	 * @return int
	 */
	public function counterForWorker(?string $accident = NULL, ?Carbon $dateStart  = NULL, ?Carbon $dateEnd  = NULL, ?int $sex=-1, ?int $fromOld = -1, ?int $toOld = -1, ?int $structure_segment = -1, ?int $profession_id = -1): int
	{
		$query = DB::table('worker_accidents as t1')
			->join('accident_types as t2', 't1.accident_type_id', 't2.id')
			->join('workers as t3', 't1.worker_id','t3.id');
		if ( ! empty($accident)) {
			$query->where('t2.name', 'like', $accident);
		}
		if ( ! empty($dateStart)) {
			$query->where('t1.accident_at', '>=',  $dateStart->toDateString());
		}
		if ( ! empty($dateEnd)) {
			$query->where('t1.accident_at', '<=', $dateEnd->toDateString());
		}
		if($sex !== -1){
			$query->where('t3.sex','=', $sex);
		}
		if($toOld !== -1){
			$query->where('t3.birth_at','<=', $toOld);
		}
		if($fromOld !== -1){
			$query->where('t3.birth_at','>=', $fromOld);
		}
		if($structure_segment !== -1){
			$query->where('t3.structure_segment_id','=', $structure_segment);
		}
		if( $profession_id !== -1){
			$query->where('t3.profession_id','=',  $profession_id);
		}
		return $query->count();
	}
}