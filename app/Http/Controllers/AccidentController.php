<?php

namespace App\Http\Controllers;

use App\Models\AccidentType;
use App\Models\Controllers\AccidentsModelController;
use App\Models\Controllers\ProfessionsModelController;
use App\Models\Repo\AccidentsModelRepo;
use App\Models\Repo\ProfessionsModelRepo;
use App\Models\WorkerAccident;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccidentController extends Controller
{

	public function accidentWorkers()
	{
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt'   => 'Новий інцидент',
				'args'  => 'data-route="' . route('accident.details', ['id' => -1]) . '"',
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt'   => 'Повернутись назад',
				'args'  => 'data-route="' . route('home') . '"',
			],
		];
		return view('pages.workers_accidents', ['search' => '', 'buttons' => $buttons]);
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function redirect(Request $request): JsonResponse
	{
		return response()->json([
			'ack' => 'redirect',
			'url' => $request->has('accident_id') ? route('accident.details', ['id' => $request->get('accident_id')]) : '/',
		]);
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function typeRedirect(Request $request): JsonResponse
	{
		return response()->json([
			'ack' => 'redirect',
			'url' => $request->has('accident_type_id') ? route('accident_type.details', ['id' => $request->get('accident_type_id')]) : '/',
		]);
	}

	/**
	 * @return Application|Factory|View
	 */
	public function details(Request $request)
	{
		$id      = (int) $request->get("id");
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt'   => 'Новий інцидент',
				'args'  => 'data-route="' . route('accident.details', ['id' => -1]) . '"',
			],
			[
				'label' => 'х',
				'class' => 'js-delete nav-link',
				'alt'   => 'Видалити інцидент',
				'args'  => 'data-route="' . route('accident.delete', ['id' => $id]) . '" data-method="POST"',
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt'   => 'Повернутись назад',
				'args'  => 'data-route="' . route('accidents.workers') . '"',
			],
		];
		$params  = [
			'buttons' => $buttons,
		];
		if ($id !== -1) {
			$accidentController = new AccidentsModelController(new AccidentsModelRepo());
			$accident           = $accidentController->findById((int) $id);
			$params['accident'] = $accident;
		}
		return view('pages.accident_details', $params);
	}

	/**
	 * @return Application|Factory|View
	 */
	public function typeDetails(Request $request)
	{
		$id      = (int) $request->get("id");
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt'   => 'Новий тип інцидентів',
				'args'  => 'data-route="' . route('accident_type.details', ['id' => -1]) . '"',
			],
			[
				'label' => 'х',
				'class' => 'js-delete nav-link',
				'alt'   => 'Видалити тип інцидентів',
				'args'  => 'data-route="' . route('accident_type.delete', ['id' => $id]) . '" data-method="POST"',
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt'   => 'Повернутись назад',
				'args'  => 'data-route="' . route('accidents.workers') . '"',
			],
		];
		$params  = [
			'buttons' => $buttons,
		];
		if ($id !== -1) {
			$accidentController     = new AccidentsModelController(new AccidentsModelRepo());
			$accidentType           = $accidentController->findTypeById((int) $id);
			$params['accidentType'] = $accidentType;
		}
		return view('pages.accident_type_details', $params);
	}

	/**
	 * @param int $id
	 * @return JsonResponse
	 */
	public function typeDelete(int $id): JsonResponse
	{
		try {
			if ($id !== -1) {
				$accidentController = new AccidentsModelController(new AccidentsModelRepo());
				$accidentType       = $accidentController->findTypeById((int) $id);
				$accidentType->setDeletedAt(now('UTC'));
				$accidentType->save();
			}
			$message     = 'Тип Інциденту було видалено';
			$messageType = 'success';
		} catch (Exception $e) {
			$message     = 'Помилка. Видалення невдале';
			$messageType = 'error';
		}
		return response()->json([
			'ack'         => 'redirect',
			'message'     => $message,
			'messageType' => $messageType,
			'url'         => route('accidents.show'),
		]);
	}

	/**
	 * @param int     $id
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function typeSave(int $id, Request $request): RedirectResponse
	{
		try {
			if ($id === -1) {
				$accidentType = new AccidentType();
				$message      = 'Тип Інциденту успішно створено';
			} else {
				$accidentController = new AccidentsModelController(new AccidentsModelRepo());
				$accidentType       = $accidentController->findTypeById((int) $id);
				$message            = 'Тип Інциденту успішно оновлено';
			}
			if ($request->has('name')) {
				$accidentType->setName($request->get('name'));
			}
			if ($id !== -1 || ($id === -1 && $request->has('name'))) {
				$accidentType->save();
			}
			$messageType = 'success';
		} catch (Exception $e) {
			$message     = 'Тип Інциденту зберегти не вдалося';
			$messageType = 'error';
		}
		return response()->redirectToRoute('accidents.show', ['message' => $message, 'messageType' => $messageType]);
	}

	/**
	 * @param int     $id
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function save(int $id, Request $request): RedirectResponse
	{
		try {
			if ($id === -1) {
				$accident = new WorkerAccident();
				$message  = 'Картку інциденту успішно створено';
			} else {
				$accidentController = new AccidentsModelController(new AccidentsModelRepo());
				$accident           = $accidentController->findById($id);
				$message            = 'Картку інциденту успішно оновлено';
			}
			if ($request->has('worker_id')) {
				$accident->setWorkerId($request->get('worker_id'));
			}
			if ($request->has('accidents_type_id')) {
				$accident->setAccidentTypeId($request->get('accidents_type_id'));
			}
			if ($request->has('accident_at')) {
				$accident->setAccidentAt(Carbon::parse($request->get('accident_at')));
			}
			if ($request->has('hours')) {
				$accident->setHoursAfterStartWorking($request->get('hours'));
			}
			if ($request->has('sick_start_at')) {
				$accident->setSickStartAt(Carbon::parse($request->get('sick_start_at')));
			}
			if ($request->has('sick_end_at')) {
				$accident->setSickEndAt(Carbon::parse($request->get('sick_end_at')));
			}
			$accident->save();
			$messageType = 'success';
		} catch (Exception $e) {
			$message     = 'Інцидент зберегти не вдалося';
			$messageType = 'error';
		}
		return response()->redirectToRoute('accidents.workers', ['message' => $message, 'messageType' => $messageType]);
	}

	/**
	 * @param int $id
	 * @return JsonResponse
	 */
	public function delete(int $id): JsonResponse
	{
		try {
			if ($id !== -1) {
				$accidentController = new AccidentsModelController(new AccidentsModelRepo());
				$accident           = $accidentController->findById($id);
				$accident->setDeletedAt(now('UTC'));
				$accident->save();
				$message     = 'Картку інциденту успішно видалено';
				$messageType = 'success';
			} else {
				$message     = 'Створення скасовано';
				$messageType = 'error';
			}
		} catch (Exception $e) {
			$message     = 'Картку інциденту видалити не вдалося';
			$messageType = 'error';
		}
		return response()->json([
			'ack'         => 'redirect',
			'message'     => $message,
			'messageType' => $messageType,
			'url'         => route('accidents.workers'),
		]);
	}

	public function show()
	{
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt'   => 'Новий тип інцидентів',
				'args'  => 'data-route="' . route('accident_type.details', ['id' => -1]) . '"',
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt'   => 'Повернутись назад',
				'args'  => 'data-route="' . route('home') . '"',
			],
		];
		return view('pages.accidents', ['search' => '', 'buttons' => $buttons]);
	}
}
