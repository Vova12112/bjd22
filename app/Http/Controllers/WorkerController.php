<?php

namespace App\Http\Controllers;

use App\Models\Controllers\WorkersModelController;
use App\Models\Repo\WorkersModelRepo;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Class WorkerController
 * @package App\Http\Controllers
 */
class WorkerController extends Controller
{
	/**
	 * @return Application|Factory|View
	 */
	public function workers()
	{
		return view('pages.workers', ['search' => '']);
	}

	public function redirect(Request $request)
	{
		return response()->json([
			'ack'=>'redirect',
			'url'=> $request->has('worker_id') ? route('worker.details', ['id' => $request->get('worker_id')]) : '/'
		]);
	}

	public function workerDetails(string $id)
	{
		$workerController = new WorkersModelController(new WorkersModelRepo());
		$worker = $workerController->findById((int)$id);
		return view('pages.worker_details', compact('worker'));
	}

}
