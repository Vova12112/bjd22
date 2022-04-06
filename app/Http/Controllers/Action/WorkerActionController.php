<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WorkerController;
use App\Models\Controllers\WorkersModelController;
use App\Models\Repo\WorkersModelRepo;
use App\Models\Worker;
use App\ValuesObject\Categories;
use App\ValuesObject\Division;
use App\ValuesObject\FamilyStatus;
use App\ValuesObject\Genders;
use App\ValuesObject\Professions;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class WorkerActionController extends Controller
{
	public function get()
	{
		$buttons       = [
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
				'args'  => 'data-route="' . route('workers') . '"',
			],
		];
		$sexes         = Genders::getSex();
		$marryStatuses = FamilyStatus::getMarryStatus();
		$divisions     = Division::getDivisions();
		$categories    = Categories::getCategories();
		$professions   = Professions::getCategories();
		return view('pages.worker_create', compact('buttons', 'sexes', 'marryStatuses', 'divisions', 'categories', 'professions'));
	}

	public function deleteWorker(Request $request): RedirectResponse
	{
		try {
			if ($request->get('id') !== -1) {
				$workerModelController = new WorkersModelController(new WorkersModelRepo());
				/*** @var Worker $worker */
				$worker = $workerModelController->findById($request->get('id'));
				$worker->setFiredAt(now('UTC'));
				$worker->save();
				$message     = 'Картку працівника видалено';
				$messageType = 'success';
			} else {
				$message     = 'Створення скасовано';
				$messageType = 'error';
			}
		} catch (Exception | Throwable $e) {
			$message     = 'Картку працівника видалити не вдалося';
			$messageType = 'error';
		}
		return response()->redirectToRoute('workers', ['message' => $message, 'messageType' => $messageType]);
	}

	public function addNewWorker(Request $request): RedirectResponse
	{
		try {
			if ($request->get('id') === '-1') {
				$worker = new Worker();
				$isNew  = TRUE;
			} else {
				$workerController = new WorkersModelController(new WorkersModelRepo());
				/*** @var Worker $worker */
				$worker = $workerController->findById((int) $request->get('id'));
				$isNew  = FALSE;
			}
			$firstName            = (string) $request->get('first-name');
			$lastName             = (string) $request->get('last-name');
			$sub_name             = (string) $request->get('sub-name');
			$sex                  = $request->get('sex') === '-' ? NULL : $request->get('sex');
			$married              = ((string) $request->get('married')) === '1';
			$birth_at             = $request->get('birth-at') ? Carbon::parse($request->get('birth-at')) : NULL;
			$body_check_at        = $request->get('body-check-at') ? Carbon::parse($request->get('body-check-at')) : NULL;
			$instructed_at        = $request->get('instructed-at') ? Carbon::parse($request->get('instructed-at')) : NULL;
			$description          = $request->get('description');
			$profession_id        = $request->get('profession-id') === '-' ? NULL : $request->get('profession-id');
			$structure_segment_id = $request->get('structure-segment-id') === '-' ? NULL : $request->get('structure-segment-id');
			if ( ! $isNew) {
				$oldFirstName            = (string) $worker->getFirstName();
				$oldLastName             = (string) $worker->getLastName();
				$oldSub_name             = (string) $worker->getSubName();
				$oldSex                  = (string) $worker->getSex();
				$oldMarried              = $worker->isMarried();
				$oldBirth_at             = $worker->getBirthAt();
				$oldBody_check_at        = $worker->getBodyCheckAt();
				$oldInstructed_at        = $worker->getInstructedAt();
				$oldDescription          = (string) $worker->getDescription();
				$oldProfession_id        = (string) $worker->getProfessionId();
				$oldStructure_segment_id = (string) $worker->getStructureSegmentId();
			}
			if ($isNew || $firstName !== $oldFirstName) {
				$worker->setFirstName($firstName);
			}
			if ($isNew || $lastName !== $oldLastName) {
				$worker->setLastName($lastName);
			}
			if ($isNew || $sub_name !== $oldSub_name) {
				$worker->setSubName($sub_name);
			}
			if ($isNew || $sex !== $oldSex) {
				$worker->setSex($sex);
			}
			if ($isNew || $married !== $oldMarried) {
				$worker->setMarried($married);
			}
			if ($isNew || $birth_at !== $oldBirth_at) {
				$worker->setBirthAt($birth_at);
			}
			if ($isNew || $body_check_at !== $oldBody_check_at) {
				$worker->setBodyCheckAt($body_check_at);
			}
			if ($isNew || $instructed_at !== $oldInstructed_at) {
				$worker->setInstructedAt($instructed_at);
			}
			if ($isNew || $description !== $oldDescription) {
				$worker->setDescription($description);
			}
			if ($isNew || $profession_id !== $oldProfession_id) {
				$worker->setProfessionId($profession_id);
			}
			if ($isNew || $structure_segment_id !== $oldStructure_segment_id) {
				$worker->setStructureSegmentId($structure_segment_id);
			}
			$worker->save();
			$message     = 'Картку працівника успішно збережено';
			$messageType = 'success';
		} catch (Exception | Throwable $e) {
			$message     = 'Картку працівника зберегти не вдалося';
			$messageType = 'error';
		}
		return response()->redirectToRoute('workers', ['message' => $message, 'messageType' => $messageType]);
	}

}
