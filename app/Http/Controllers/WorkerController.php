<?php

namespace App\Http\Controllers;

use App\Models\Controllers\WorkersModelController;
use App\Models\Repo\WorkersModelRepo;
use App\Models\Worker;
use Carbon\Carbon;
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
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt' => 'Новий працівник',
				'args' => 'data-route="' . route('worker.create.page') . '"'
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt' => 'Повернутись назад',
				'args' => 'data-route="' . route('home') . '"',
			],
		];
		return view('pages.workers', ['search' => '', 'buttons' => $buttons]);
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
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create',
				'alt' => 'Новий працівник'
			],
			[
				'label' => 'х',
				'class' => 'js-delete',
				'alt' => 'Звільнити працівника'
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt' => 'Повернутись назад',
				'args' => 'data-route="' . route('workers') . '"',
			],
		];
		return view('pages.worker_details', compact('worker', 'buttons'));
	}

	public function workerCreate(?string $first_name, ?string $last_name, ?string $sub_name, ?int $sex, ?bool $married, ?Carbon $birth_at, ?Carbon $body_check_at, ?Carbon $instructed_at, ?string $description, ?int $profession_id, ?int $structure_segment_id)
	{
		$worker = new Worker();
		$worker->setFirstName( $first_name);
		$worker->setLastName( $last_name);
		$worker->setSubName( $sub_name);
		$worker->setSex($sex);
		$worker->setMarried($married);
		$worker->setBirthAt( $birth_at);
		$worker->setBodyCheckAt( $body_check_at);
		$worker->setInstructedAt( $instructed_at);
		$worker->setDescription( $description);
		$worker->setProfessionId($profession_id);
		$worker->setStructureSegmentId($structure_segment_id);
		$worker->save();
	}

	public function workerChange(int $id, ?string $first_name, ?string $last_name, ?string $sub_name, ?int $sex, ?bool $married, ?Carbon $birth_at, ?Carbon $body_check_at, ?Carbon $instructed_at, ?string $description, ?int $profession_id, ?int $structure_segment_id)
	{
		$workerController = new WorkersModelController(new WorkersModelRepo());
		$worker = $workerController->findById($id);
		$worker->setFirstName( $first_name);
		$worker->setLastName( $last_name);
		$worker->setSubName( $sub_name);
		$worker->setSex($sex);
		$worker->setMarried($married);
		$worker->setBirthAt( $birth_at);
		$worker->setBodyCheckAt( $body_check_at);
		$worker->setInstructedAt( $instructed_at);
		$worker->setDescription( $description);
		$worker->setProfessionId($profession_id);
		$worker->setStructureSegmentId($structure_segment_id);
		$worker->save();
	}

	public function workerDelete($id)
	{
		$workerController = new WorkersModelController(new WorkersModelRepo());
		$worker = $workerController->findById((int)$id);
		$worker->setDeletedAt(now('UTC'));
	}

}
