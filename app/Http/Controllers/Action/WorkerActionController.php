<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WorkerController;
use App\Models\Controllers\WorkersModelController;
use App\Models\Repo\WorkersModelRepo;
use App\Traits\ExceptionResponse;
use App\ValuesObject\Categories;
use App\ValuesObject\Division;
use App\ValuesObject\FamilyStatus;
use App\ValuesObject\Genders;
use App\ValuesObject\Professions;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkerActionController extends Controller
{
	use ExceptionResponse;
	public function get()
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
				'args' => 'data-route="' . route('workers') . '"',
			],
		];
		$sexes = Genders::getSex();
		$marryStatuses = FamilyStatus::getMarryStatus();
		$divisions = Division::getDivisions();
		$categories = Categories::getCategories();
		$professions = Professions::getCategories();
		return view('pages.worker_create',compact( 'buttons','sexes','marryStatuses','divisions','categories','professions'));
	}

	public function deleteWorker(Request $request): RedirectResponse
	{
		if ( $request->get('id') !== '-1'){
			$workerModelController        = new WorkersModelController(new WorkersModelRepo());
			$workerController = new WorkerController();
			//TODO баг с выткаскиванием реквеста, из-за метода пост... Сижу думаю, может страницу лучше сделать сразу с вопросом точно ли...
			$workerController->workerDelete( $workerModelController -> findById( (int) $request->get('id') ));
			return response()->redirectToRoute('workers');
		}
		else return response()->redirectToRoute('error.unexpected');
	}

	public function addNewWorker(Request $request)
	{
		try {
			if ($request->get('id') === '-1') {
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
					$request->get('structure-segment-id')
				);
			} else {
				$workerController        = new WorkersModelController(new WorkersModelRepo());
				$worker                  = $workerController->findById((int) $request->get('id'));
				$firstName               = (string) $request->get('first_name');
				$lastName                = (string) $request->get('last_name');
				$sub_name                = (string) $request->get('sub-name');
				$sex                     = $request->get('sex');
				$married                 = (string) $request->get('married');
				$birth_at                = $request->get('birth-at');
				$body_check_at           = $request->get('body-check-at');
				$instructed_at           = $request->get('instructed-at');
				$description             = $request->get('description');
				$profession_id           = $request->get('profession-id');
				$structure_segment_id    = $request->get('structure-segment-id');
				$oldFirstName            = (string) $worker->getFirstName();
				$oldLastName             = (string) $worker->getLastName();
				$oldSub_name             = (string) $worker->getFirstName();
				$oldSex                  = (string) $worker->getFirstName();
				$oldMarried              = (string) $worker->getFirstName();
				$oldBirth_at             = (string) $worker->getFirstName();
				$oldBody_check_at        = (string) $worker->getFirstName();
				$oldInstructed_at        = (string) $worker->getFirstName();
				$oldDescription          = (string) $worker->getFirstName();
				$oldProfession_id        = (string) $worker->getFirstName();
				$oldStructure_segment_id = (string) $worker->getFirstName();
				if ($firstName !== $oldFirstName) {
					$worker->setFirstName($firstName);
				}
				if ($lastName !== $oldLastName) {
					$worker->setLastName($lastName);
				}
				if ($sub_name !== $oldSub_name) {
					$worker->setSubName($sub_name);
				}
				if ($sex !== $oldSex) {
					$worker->setSex($sex);
				}
				if ($married !== $oldMarried) {
					$worker->setMarried($lastName);
				}
				if ($birth_at !== $oldBirth_at) {
					$worker->setBirthAt($birth_at);
				}
				if ($body_check_at !== $oldBody_check_at) {
					$worker->setBodyCheckAt($body_check_at);
				}
				if ($instructed_at !== $oldInstructed_at) {
					$worker->setInstructedAt($instructed_at);
				}
				if ($description !== $oldDescription) {
					$worker->setDescrtption($description);
				}
				if ($profession_id !== $oldProfession_id) {
					$worker->setProfessionId($profession_id);
				}
				if ($structure_segment_id !== $oldStructure_segment_id) {
					$worker->setStructureSegmentId($structure_segment_id);
				}
			}
			return response()->redirectToRoute('workers');
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

}
