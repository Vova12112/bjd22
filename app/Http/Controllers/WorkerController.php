<?php

namespace App\Http\Controllers;

use App\Models\Controllers\WorkersModelController;
use App\Models\Repo\WorkersModelRepo;
use App\Models\Worker;
use App\Traits\ExceptionResponse;
use App\ValuesObject\Categories;
use App\ValuesObject\Division;
use App\ValuesObject\FamilyStatus;
use App\ValuesObject\Genders;
use App\ValuesObject\Professions;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class WorkerController
 * @package App\Http\Controllers
 */
class WorkerController extends Controller
{
	use ExceptionResponse;

	public function workers()
	{
		try {

			$buttons = [
				[
					'label' => '+',
					'class' => 'js-create nav-link',
					'alt'   => 'Новий працівник',
					'args'  => 'data-route="' . route('worker.create.page') . '"',
				],
				[
					'label' => '<',
					'class' => 'js-back nav-link',
					'alt'   => 'Повернутись назад',
					'args'  => 'data-route="' . route('home') . '"',
				],
			];
			return view('pages.workers', ['search' => '', 'buttons' => $buttons]);
		} catch (Exception $e) {
			return $this->ajaxFail(
				$e,
				'ОЙ - ОЙ - ОЙ',
				sprintf(
					'Sheeeeeeep %s', 'happens'
				)
			);
		}
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function redirect(Request $request): JsonResponse
	{
		return response()->json([
			'ack' => 'redirect',
			'url' => $request->has('worker_id') ? route('worker.details', ['id' => $request->get('worker_id')]) : '/',
		]);
	}

	public function workerDetails(string $id)
	{
		$workerController = new WorkersModelController(new WorkersModelRepo());
		$worker           = $workerController->findById((int) $id);
		$sexes            = Genders::getSex();
		$marryStatuses    = FamilyStatus::getMarryStatus();
		$divisions        = Division::getDivisions();
		$categories       = Categories::getCategories();
		$professions      = Professions::getCategories();
		$buttons          = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt'   => 'Новий працівник',
				'args'  => 'data-route="' . route('worker.create.page') . '"',
			],
			[
				'label' => 'х',
				'class' => 'js-delete nav-link',
				'args'  => 'data-route="' . route('worker.delete.page') . '"',
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt'   => 'Повернутись назад',
				'args'  => 'data-route="' . route('workers') . '"',
			],
		];
		return view('pages.worker_details', compact('worker', 'buttons', 'sexes', 'marryStatuses', 'divisions', 'categories', 'professions'));
	}

	public function workerCreate($first_name, $last_name, $sub_name, $sex, $married, $birth_at, $body_check_at, $instructed_at, $description, $profession_id, $structure_segment_id): JsonResponse
	{
		try {
			$worker = new Worker();
			$this->extracted($worker, $first_name, $last_name, $sub_name, $sex, $married, $birth_at, $body_check_at, $instructed_at, $description, $profession_id, $structure_segment_id);
			return response()->json(['ack' => 'reload', 'message' => 'Worker Save success']);
		} catch (Exception $e) {
			return $this->ajaxFail(
				$e,
				'ОЙ - ОЙ - ОЙ',
				sprintf(
					'Sheeeeeeep %s', 'happens'
				)
			);
		}
	}

	public function workerChange(int $id, ?string $first_name, ?string $last_name, ?string $sub_name, ?int $sex, ?bool $married, ?Carbon $birth_at, ?Carbon $body_check_at, ?Carbon $instructed_at, ?string $description, ?int $profession_id, ?int $structure_segment_id): JsonResponse
	{
		try {
			$workerController = new WorkersModelController(new WorkersModelRepo());
			$worker           = $workerController->findById($id);
			$this->extracted($worker, $first_name, $last_name, $sub_name, $sex, $married, $birth_at, $body_check_at, $instructed_at, $description, $profession_id, $structure_segment_id);
			return response()->json(['ack' => 'reload', 'message' => 'Worker Changed success']);
		} catch (Exception $e) {
			return $this->ajaxFail(
				$e,
				'ОЙ - ОЙ - ОЙ',
				sprintf(
					'Sheeeeeeep %s', 'happens'
				)
			);
		}
	}

	public function workerDelete($id)
	{
		$workerController = new WorkersModelController(new WorkersModelRepo());
		$worker           = $workerController->findById((int) $id);
		$worker->setDeletedAt(now('UTC'));
	}

	/**
	 * @param             $worker
	 * @param string|null $first_name
	 * @param string|null $last_name
	 * @param string|null $sub_name
	 * @param int|null    $sex
	 * @param bool|null   $married
	 * @param Carbon|null $birth_at
	 * @param Carbon|null $body_check_at
	 * @param Carbon|null $instructed_at
	 * @param string|null $description
	 * @param int|null    $profession_id
	 * @param int|null    $structure_segment_id
	 * @return void
	 */
	public function extracted($worker, ?string $first_name, ?string $last_name, ?string $sub_name, ?int $sex, ?bool $married, ?Carbon $birth_at, ?Carbon $body_check_at, ?Carbon $instructed_at, ?string $description, ?int $profession_id, ?int $structure_segment_id): void
	{
		$worker->setFirstName($first_name);
		$worker->setLastName($last_name);
		$worker->setSubName($sub_name);
		$worker->setSex($sex);
		$worker->setMarried($married);
		$worker->setBirthAt($birth_at);
		$worker->setBodyCheckAt($body_check_at);
		$worker->setInstructedAt($instructed_at);
		$worker->setDescription($description);
		$worker->setProfessionId($profession_id);
		$worker->setStructureSegmentId($structure_segment_id);
		$worker->save();
	}

}
