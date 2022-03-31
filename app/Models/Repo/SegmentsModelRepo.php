<?php

namespace App\Models\Repo;

use App\Models\Segment;
use App\Models\StructureSegment;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use RuntimeException;

/**
 * Class SegmentsModelRepo
 * @package App\Models\Repo
 */
class SegmentsModelRepo
{

	/**
	 * @param int         $currentPage
	 * @param int         $pageSize
	 * @param string      $sortField
	 * @param string      $sortOrder
	 * @param string|null $search
	 * @return LengthAwarePaginator
	 */
	public function fetchPageSegments(int $currentPage, int $pageSize, string $sortField, string $sortOrder, ?string $search): LengthAwarePaginator
	{
		$query = DB::table('structure_segments as t1')
			->select($this->getSegmentsFields());
		if ( ! empty($search)) {
			$query->where(
				static function(Builder $q) use ($search) {
					$q->where('t1.name', 'like', '%' . $search . '%');
				}
			);
		}
		$query->whereNull('t1.deleted_at');
		$query = $this->checkSegmentsOrder($query, $sortField, $sortOrder);
		return $query->paginate($pageSize, ['*'], 'page', $currentPage);
	}

	/**
	 * @param int $id
	 * @return mixed
	 */
	public function findById(int $id)
	{
		$segment = StructureSegment::where('id', '=', $id)->first();
		if ($segment === NULL) {
			throw new RuntimeException("Відділ не знайдено");
		}
		return $segment;
	}

	/**
	 * @return string[]
	 */
	private function getSegmentsFields(): array
	{
		return [
			't1.id',
			't1.name',
		];
	}

	/**
	 * @param Builder $query
	 * @param string  $sortField
	 * @param string  $sortOrder
	 * @return Builder
	 */
	private function checkSegmentsOrder(Builder $query, string $sortField, string $sortOrder): Builder
	{
		$query->orderBy('t1.' . $sortField, $sortOrder);
		return $query;
	}
}