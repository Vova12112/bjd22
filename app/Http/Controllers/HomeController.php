<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Traits\ExceptionResponse;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
	use ExceptionResponse;

	public function index()
	{
		return view('pages.home');
	}

	public
	function unexpectedError()
	{
		return view('pages.errors.unexpected');
	}
}
