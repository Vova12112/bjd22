<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WorkerController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class WorkerActionController extends Controller
{
	public function get(){
		return view('pages.worker_create');
	}
	public function addNewWorker(Request $request)
	{
		try {
			$workerController = new WorkerController();
			$workerController->workerCreate(
				$request->get('first-name'),
				$request->get('last-name'),
				$request->get('sub-name'),
				$request->get('sex'),
				$request->get('married'),
				$request->get('birth-at'),
				$request->get('body-check-at'),
				$request->get('instructed-at'),
				$request->get('description'),
				$request->get('profession-id'),
				$request->get('structure-segment-id'));
			return route('worker.create.page');
		} catch (Exception $e) {
			return response()->json(['ack' => 'fail', 'message' => $e->getMessage()]);
		}
	}


}
