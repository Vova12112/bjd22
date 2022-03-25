<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class TestController
 * @package App\Http\Controllers
 */
class TestController extends Controller
{
	/*** @return Application|Factory|View */
	public function datapicker()
	{
		return view('test.datapicker');
	}
}
