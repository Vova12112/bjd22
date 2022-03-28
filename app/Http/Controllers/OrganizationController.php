<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class OrganizationController
 * @package App\Http\Controllers
 */
class OrganizationController extends Controller
{
	/*** @return Application|Factory|View */
	public function organization()
	{
		return view('pages.organization');
	}

	/*** @return Application|Factory|View */
	public function organizationStructure()
	{
		return view('pages.organization_structure');
	}
}
