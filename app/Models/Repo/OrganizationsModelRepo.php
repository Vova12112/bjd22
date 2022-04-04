<?php

namespace App\Models\Repo;

use App\Models\Organization;
use DB;
use RuntimeException;

/**
 * Class OrganizationsModelRepo
 * @package App\Models\Repo
 */
class OrganizationsModelRepo
{

	/*** @return Organization */
	public function getOrganization(): Organization
	{
		return Organization::where('id', '=', '1')->first();
	}
}