<?php

namespace App\Models\Repo;

use App\Models\AccidentType;
use App\Models\Worker;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use RuntimeException;

/**
 * Class AccidentsModelRepo
 * @package App\Models\Repo
 */
class AccidentsModelRepo
{

	/**
	 * @param int         $currentPage
	 * @param int         $pageSize
	 * @param string      $sortField
	 * @param string      $sortOrder
	 * @param string|null $search

	 * @return LengthAwarePaginator
	 */
	public function fetchPageAccidents(int $currentPage, int $pageSize, string $sortField, string $sortOrder, ?string $search): LengthAwarePaginator
	{
		$query = DB::table('accident_types as t1')
			->select($this->getAccidentsFields());
		if ( ! empty($search)) {
			$query->where(
				static function(Builder $q) use ($search) {
					$q->where('t1.name', 'like',$search);
				}
			);
		}
		$query->whereNull('t1.deleted_at');
		$query = $this->checkAccidentsOrder($query, $sortField, $sortOrder);
		return $query->paginate($pageSize, ['*'], 'page', $currentPage);
	}
	

	/**
	 * @return string[]
	 */
	private function getAccidentsFields(): array
	{
		return [
			't1.id',
			't1.name',
			't1.created_at',
			't1.updated_at',
		];
	}
	private function checkAccidentsOrder(Builder $query, string $sortField, string $sortOrder): Builder
	{
		$query->orderBy('t1.' . $sortField, $sortOrder);
		return $query;
	}
}