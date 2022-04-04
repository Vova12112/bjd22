<?php

namespace App\Models\Repo;

use App\Models\Profession;
use App\Models\ProfessionCategory;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use RuntimeException;

/**
 * Class ProfessionsModelRepo
 * @package App\Models\Repo
 */
class ProfessionsModelRepo
{

	/**
	 * @param int         $currentPage
	 * @param int         $pageSize
	 * @param string      $sortField
	 * @param string      $sortOrder
	 * @param string|null $search
	 * @return LengthAwarePaginator
	 */
	public function fetchPageProfessions(int $currentPage, int $pageSize, string $sortField, string $sortOrder, ?string $search): LengthAwarePaginator
	{
		$query = DB::table('professions as t1')
			->select($this->getProfessionsFields())
			->join('profession_categories as t2', 't1.profession_category_id', 't2.id');
		if ( ! empty($search)) {
			$query->where(
				static function(Builder $q) use ($search) {
					$q->where('t1.name', 'like', '%' . $search . '%');
					$q->orWhere('t2.name', 'like', '%' . $search . '%');
				}
			);
		}
		$query->whereNull('t1.deleted_at');
		$query = $this->checkProfessionsOrder($query, $sortField, $sortOrder);
		return $query->paginate($pageSize, ['*'], 'page', $currentPage);
	}

	/**
	 * @param int $id
	 * @return mixed
	 */
	public function findById(int $id)
	{
		$profession = Profession::where('id', '=', $id)->first();
		if ($profession === NULL) {
			throw new RuntimeException("Посаду не знайдено");
		}
		return $profession;
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function findCategoryById($id)
	{
		$category = ProfessionCategory::where('id', '=', $id)->first();
		if ($category === NULL) {
			throw new RuntimeException("Категорію не знайдено");
		}
		return $category;
	}

	/**
	 * @param int    $currentPage
	 * @param int    $pageSize
	 * @param string $sortField
	 * @param string $sortOrder
	 * @param string $search
	 * @return LengthAwarePaginator
	 */
	public function fetchPageProfessionCategories(int $currentPage, int $pageSize, string $sortField, string $sortOrder, string $search): LengthAwarePaginator
	{
		$query = DB::table('profession_categories as t1')
			->select($this->getProfessionCategoriesFields());
		if ( ! empty($search)) {
			$query->where('t1.name', 'like', '%' . $search . '%');
		}
		$query->whereNull('t1.deleted_at');
		$query = $this->checkProfessionsOrder($query, $sortField, $sortOrder);
		return $query->paginate($pageSize, ['*'], 'page', $currentPage);
	}

	/**
	 * @return array
	 */
	public function getCategoriesList(): array
	{
		$categories = ProfessionCategory::all();
		return $categories->pluck('name', 'id')->toArray();
	}

	/**
	 * @return string[]
	 */
	private function getProfessionsFields(): array
	{
		return [
			't1.id',
			't1.name as profession_name',
			't2.name',
		];
	}

	/**
	 * @return string[]
	 */
	private function getProfessionCategoriesFields(): array
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
	private function checkProfessionsOrder(Builder $query, string $sortField, string $sortOrder): Builder
	{
		$query->orderBy('t1.' . $sortField, $sortOrder);
		return $query;
	}
}