<?php

namespace App\Models\Controllers;

use App\Models\Organization;
use App\Models\Repo\OrganizationsModelRepo;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class OrganizationsModelController
 * @package App\Models\Controllers
 */
class OrganizationsModelController
{

	/*** @var OrganizationsModelRepo */
	public OrganizationsModelRepo $repo;

	/**
	 * UserController constructor.
	 * @param OrganizationsModelRepo $repo
	 */
	public function __construct(OrganizationsModelRepo $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * @return Organization
	 */
	public function getOrganization(): Organization
	{
		return $this->repo->getOrganization();
	}

}