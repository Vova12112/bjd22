<?php

namespace App\Models\Controllers;

use App\Models\Repo\SegmentsModelRepo;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class SegmentsModelController
 * @package App\Models\Controllers
 */
class SegmentsModelController
{

	/*** @var SegmentsModelRepo */
	public SegmentsModelRepo $repo;

	/**
	 * UserController constructor.
	 * @param SegmentsModelRepo $repo
	 */
	public function __construct(SegmentsModelRepo $repo)
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
	public function fetchPageSegments(int $currentPage, int $pageSize, string $sortField, string $sortOrder, ?string $search): LengthAwarePaginator
	{
		return $this->repo->fetchPageSegments($currentPage, $pageSize, $sortField, $sortOrder, $search);
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