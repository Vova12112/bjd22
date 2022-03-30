<?php

namespace App\Models\Repo;

use App\Models\Worker;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use RuntimeException;

/**
 * Class WorkersModelRepo
 * @package App\Models\Repo
 */
class WorkersModelRepo
{

	/**
	 * @param int         $currentPage
	 * @param int         $pageSize
	 * @param string      $sortField
	 * @param string      $sortOrder
	 * @param string|null $search
	 * @param array       $filters
	 * @return LengthAwarePaginator
	 */
	public function fetchPageWorkers(int $currentPage, int $pageSize, string $sortField, string $sortOrder, ?string $search, array $filters): LengthAwarePaginator
	{
		$query = DB::table('workers as t1')
			->select($this->getWorkersFields())
			->join('structure_segments as t2', 't1.structure_segment_id', 't2.id')
			->join('professions as t3', 't1.profession_id', 't3.id');
		if ( ! empty($search)) {
			$query->where(
				static function(Builder $q) use ($search) {
					$q->where('t1.first_name', 'like', '%' . $search . '%')
						->orWhere('t1.last_name', 'like', '%' . $search . '%')
						->orWhere('t1.sub_name', 'like', '%' . $search . '%')
						->orWhere('t2.name', 'like', '%' . $search . '%')
						->orWhere('t3.name', 'like', '%' . $search . '%');
				}
			);
		}
		$query->whereNull('t1.fired_at');
		$query = $this->checkWorkersOrder($query, $sortField, $sortOrder);
		return $query->paginate($pageSize, ['*'], 'page', $currentPage);
	}

	/**
	 * @param int $id
	 * @return mixed
	 */
	public function findById(int $id)
	{
		$worker = Worker::where('id', '=', $id)->first();
		if ($worker === NULL) {
			throw new RuntimeException("Працівника не знайдено");
		}
		return $worker;
	}

	/**
	 * @return string[]
	 */
	private function getWorkersFields(): array
	{
		return [
			't1.id',
			't1.first_name',
			't1.last_name',
			't1.sub_name',
			't2.name as structure_segments',
			't3.name as profession',
		];
	}

	/**
	 * @param Builder $query
	 * @param string  $sortField
	 * @param string  $sortOrder
	 * @return Builder
	 */
	private function checkWorkersOrder(Builder $query, string $sortField, string $sortOrder): Builder
	{
		if ($sortField === 'structure_segments') {
			$query->orderBy('t2.name', $sortOrder);
		} else if ($sortField === 'profession') {
			$query->orderBy('t3.name', $sortOrder);
		} else {
			$query->orderBy('t1.' . $sortField, $sortOrder);
		}
		return $query;
	}
}