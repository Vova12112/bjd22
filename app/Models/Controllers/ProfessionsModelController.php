<?php

namespace App\Models\Controllers;

use App\Models\Repo\ProfessionsModelRepo;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ProfessionsModelController
 * @package App\Models\Controllers
 */
class ProfessionsModelController
{

	/*** @var ProfessionsModelRepo */
	public ProfessionsModelRepo $repo;

	/**
	 * UserController constructor.
	 * @param ProfessionsModelRepo $repo
	 */
	public function __construct(ProfessionsModelRepo $repo)
	{
		$this->repo = $repo;
	}

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
		return $this->repo->fetchPageProfessions($currentPage, $pageSize, $sortField, $sortOrder, $search);
	}

	/**
	 * @param int $id
	 * @return mixed
	 */
	public function findById(int $id)
	{
		return $this->repo->findById($id);
	}

	/**
	 * @return array
	 */
	public function getCategoriesList(): array
	{
		return $this->repo->getCategoriesList();
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function findCategoryById($id)
	{
		return $this->repo->findCategoryById($id);
	}

	/**
	 * @param int    $currentPage
	 * @param int    $pageSize
	 * @param string $sortField
	 * @param string $sortOrder
	 * @param string $search
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function fetchPageProfessionCategories(int $currentPage, int $pageSize, string $sortField, string $sortOrder, string $search): \Illuminate\Contracts\Pagination\LengthAwarePaginator
	{
		return $this->repo->fetchPageProfessionCategories($currentPage, $pageSize, $sortField, $sortOrder, $search);
	}

}