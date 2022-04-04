<?php

namespace App\Models\Repo;

use App\Models\AccidentType;
use App\Models\Worker;
use Carbon\Carbon;
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
	 * @param int    $currentPage
	 * @param int    $pageSize
	 * @param string $sortField
	 * @param string $sortOrder
	 * @param string $search
	 * @param array  $filters
	 * @return LengthAwarePaginator
	 */
	public function fetchPageWorkersAccidents(int $currentPage, int $pageSize, string $sortField, string $sortOrder, string $search, array $filters): LengthAwarePaginator
	{
		$query = DB::table('worker_accidents as t1')
			->select($this->getWorkersAccidentsFields())
			->join('workers as t2', 't1.worker_id', 't2.id')
			->join('accident_types as t3', 't1.accident_type_id', 't3.id')
			->join('structure_segments as t4', 't2.structure_segment_id', 't4.id')
			->join('professions as t5', 't2.profession_id', 't5.id')
			->join('profession_categories as t6', 't5.profession_category_id', 't6.id');
		if ( ! empty($search)) {
			$query->where(
				static function(Builder $q) use ($search) {
					$q->where('t2.first_name', 'like', '%' . $search . '%')
						->orWhere('t2.last_name', 'like', '%' . $search . '%')
						->orWhere('t2.sub_name', 'like', '%' . $search . '%')
						->orWhere('t3.name', 'like', '%' . $search . '%')
						->orWhere('t4.name', 'like', '%' . $search . '%')
						->orWhere('t5.name', 'like', '%' . $search . '%');
				}
			);
		}
		if ( ! empty($filters)) {
			$query = $this->checkWorkersAccidentsFilter($query, $filters);
		}
		$query->whereNull('t1.deleted_at');
		$query = $this->checkWorkersAccidentsOrder($query, $sortField, $sortOrder);
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

	/**
	 * @param Builder $query
	 * @param string  $sortField
	 * @param string  $sortOrder
	 * @return Builder
	 */
	private function checkAccidentsOrder(Builder $query, string $sortField, string $sortOrder): Builder
	{
		$query->orderBy('t1.' . $sortField, $sortOrder);
		return $query;
	}

	/**
	 * @return string[]
	 */
	private function getWorkersAccidentsFields(): array
	{
		return [
			't1.id',
			't1.worker_id',
			't1.accident_type_id',
			't1.sick_start_at',
			't1.sick_end_at',
			't1.accident_at',
			't1.hours_after_start_working',
			't2.first_name',
			't2.last_name',
			't2.sub_name',
			't2.sex',
			't2.married',
			't2.body_check_at',
			't2.instructed_at',
			't2.fired_at',
			't3.name as accident_name',
			't4.name as segment_name',
			't5.name as profession_name',
			't6.name as category_name',
		];
	}

	/**
	 * @param Builder $query
	 * @param string  $sortField
	 * @param string  $sortOrder
	 * @return Builder
	 */
	private function checkWorkersAccidentsOrder(Builder $query, string $sortField, string $sortOrder): Builder
	{
		if ($sortField === 'category_name') {
			$query->orderBy('t6.name', $sortOrder);
		} else if ($sortField === 'profession_name') {
			$query->orderBy('t5.name', $sortOrder);
		} else if ($sortField === 'segment_name') {
			$query->orderBy('t4.name', $sortOrder);
		} else if ($sortField === 'accident_name') {
			$query->orderBy('t3.name', $sortOrder);
		} else if ($sortField === 'full_name') {
			$query->orderBy('t2.last_name', $sortOrder);
		} else if ($sortField === 'first_name') {
			$query->orderBy('t2.first_name', $sortOrder);
		} else if ($sortField === 'last_name') {
			$query->orderBy('t2.last_name', $sortOrder);
		} else if ($sortField === 'sub_name') {
			$query->orderBy('t2.sub_name', $sortOrder);
		} else {
			$query->orderBy('t1.' . $sortField, $sortOrder);
		}
		return $query;
	}

	private function checkWorkersAccidentsFilter(Builder $query, array $filters): Builder
	{
		if ( ! empty($filters['from'])) {
			$dateFrom = Carbon::parse($filters['from']);
			$query->where('t1.accident_at', '>', $dateFrom->toDateTimeString());
		}
		if ( ! empty($filters['to'])) {
			$dateTo = Carbon::parse($filters['to']);
			$query->where('t1.accident_at', '<', $dateTo->toDateTimeString());
		}
		if ( ! empty($filters['first_name'])) {
			$query->where('t2.first_name', 'like', '%' . $filters['first_name'] . '%');
		}
		if ( ! empty($filters['last_name'])) {
			$query->where('t2.last_name', 'like', '%' . $filters['last_name'] . '%');
		}
		if ( ! empty($filters['sub_name'])) {
			$query->where('t2.sub_name', 'like', '%' . $filters['sub_name'] . '%');
		}
		if ( ! empty($filters['sex'])) {
			$query->where('t2.sex', '=', $filters['sex']);
		}
		if ( ! empty($filters['profession_id'])) {
			$query->where('t2.profession_id', '=', $filters['profession_id']);
		}
		if ( ! empty($filters['category_id'])) {
			$query->where('t5.profession_category_id', '=', $filters['category_id']);
		}
		if ( ! empty($filters['segment_id'])) {
			$query->where('t2.structure_segment_id', '=', $filters['segment_id']);
		}
		if ( ! empty($filters['accidents_type_id'])) {
			$query->where('t1.accident_type_id', '=', $filters['accidents_type_id']);
		}
		return $query;
	}
}