<?php

namespace App\Models\Controllers;

use App\Models\Repo\AccidentsModelRepo;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class AccidentsModelController
 * @package App\Models\Controllers
 */
class AccidentsModelController
{

	/*** @var  AccidentsModelRepo */
	public AccidentsModelRepo $repo;

	/**
	 * UserController constructor.
	 * @param AccidentsModelRepo $repo
	 */
	public function __construct(AccidentsModelRepo $repo)
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
	public function fetchPageAccidents(int $currentPage, int $pageSize, string $sortField, string $sortOrder, ?string $search): LengthAwarePaginator
	{
		fetchPageAccidents($currentPage, $pageSize, $sortField, $sortOrder, $search);
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
		return $this->repo->fetchPageWorkersAccidents($currentPage, $pageSize, $sortField, $sortOrder, $search, $filters);
	}

}