<?php

namespace App\Models\Controllers;

use App\Models\Repo\WorkersModelRepo;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class WorkersModelController
 * @package App\Models\Controllers
 */
class WorkersModelController
{

	/*** @var WorkersModelRepo */
	public WorkersModelRepo $repo;

	/**
	 * UserController constructor.
	 * @param WorkersModelRepo $repo
	 */
	public function __construct(WorkersModelRepo $repo)
	{
		$this->repo = $repo;
	}

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
		return $this->repo->fetchPageWorkers($currentPage, $pageSize, $sortField, $sortOrder, $search, $filters);
	}

	/**
	 * @param int $id
	 * @return mixed
	 */
	public function findById(int $id)
	{
		return $this->repo->findById($id);
	}

}