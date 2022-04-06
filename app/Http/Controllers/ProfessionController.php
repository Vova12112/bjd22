<?php

namespace App\Http\Controllers;

use App\Models\Controllers\ProfessionsModelController;
use App\Models\Profession;
use App\Models\ProfessionCategory;
use App\Models\Repo\ProfessionsModelRepo;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class ProfessionController
 * @package App\Http\Controllers
 */
class ProfessionController extends Controller
{
	/*** @return Application|Factory|View */
	public function professions()
	{
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt'   => 'Нова посада',
				'args'  => 'data-route="' . route('profession.details', ['id' => -1]) . '"',
			],
			[
				'label' => '...',
				'class' => 'js-categories js-popup-noscript',
				'args'  => 'data-popup="categories-popup" data-popup-class="js-categories-popup"',
				'alt'   => 'Категорії професій',
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt'   => 'Повернутись назад',
				'args'  => 'data-route="' . route('home') . '"',
			],
		];
		return view('pages.professions', ['search' => '', 'buttons' => $buttons]);
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
				$profession = new Profession();
			} else {
				$professionController = new ProfessionsModelController(new ProfessionsModelRepo());
				$profession           = $professionController->findById($id);
			}
			if ($request->has('name')) {
				$profession->setName($request->get('name'));
			}
			if ($request->has('category_id')) {
				$profession->setProfessionCategoryId($request->get('category_id'));
			}
			if ($id !== -1 || ($id === -1 && ($request->has('name') || $request->has('category_id')))) {
				$profession->save();
				$message     = 'Посаду успішно збережено';
				$messageType = 'success';
			} else {
				$message     = 'Створення скасовано';
				$messageType = 'error';
			}
		} catch (Exception $e) {
			$message     = 'Посаду зберегти не вдалося';
			$messageType = 'error';
		}
		return response()->redirectToRoute('professions', ['message' => $message, 'messageType' => $messageType]);
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function redirect(Request $request): JsonResponse
	{
		return response()->json([
			'ack' => 'redirect',
			'url' => $request->has('profession_id') ? route('profession.details', ['id' => $request->get('profession_id')]) : '/',
		]);
	}

	/**
	 * @param int $id
	 * @return JsonResponse
	 */
	public function delete(int $id): JsonResponse
	{
		try {
			if ($id !== -1) {
				$professionController = new ProfessionsModelController(new ProfessionsModelRepo());
				$profession           = $professionController->findById($id);
				$profession->setDeletedAt(now('UTC'));
				$profession->save();
			}
			$message     = 'Посаду успішно видалено';
			$messageType = 'success';
		} catch (Exception $e) {
			$message     = 'Посаду видалити не вдалося';
			$messageType = 'error';
		}
		return response()->json([
			'ack'         => 'redirect',
			'url'         => route('professions'),
			'message'     => $message,
			'messageType' => $messageType,
		]);
	}

	/**
	 * @param int $id
	 * @return Application|Factory|View
	 */
	public function professionDetails(int $id)
	{
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt'   => 'Нова посада',
				'args'  => 'data-route="' . route('profession.details', ['id' => -1]) . '"',
			],
			[
				'label' => 'х',
				'class' => 'js-delete nav-link',
				'alt'   => 'Видалити посаду',
				'args'  => 'data-route="' . route('profession.delete', ['id' => $id]) . '" data-method="POST"',
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt'   => 'Повернутись назад',
				'args'  => 'data-route="' . route('professions') . '"',
			],
		];
		$params  = [
			'buttons' => $buttons,
		];
		if ($id !== -1) {
			$professionController = new ProfessionsModelController(new ProfessionsModelRepo());
			$profession           = $professionController->findById((int) $id);
			$params['profession'] = $profession;
		}
		return view('pages.professions_details', $params);
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function categoryDetails(Request $request): JsonResponse
	{
		try {
			if ($request->has('id')) {
				$professionController = new ProfessionsModelController(new ProfessionsModelRepo());
				$professionCategory   = $professionController->findCategoryById($request->get('id'));
			}
			return response()->json([
				'ack'  => 'success',
				'html' => view(
					'elements.category_popup_section',
					[
						'title'    => isset($professionCategory) ? $professionCategory->getName() : 'Нова категорія',
						'category' => $professionCategory ?? NULL,
					]
				)->render(),
			]);
		} catch (Exception $e) {
			return response()->json([
				'ack'  => 'success',
				'html' => 'Помилка',
			]);
		}
	}

	/**
	 * @param int     $id
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function categorySave(int $id, Request $request): JsonResponse
	{
		try {
			if ($id === -1) {
				$professionCategory = new ProfessionCategory();
			} else {
				$professionController = new ProfessionsModelController(new ProfessionsModelRepo());
				$professionCategory   = $professionController->findCategoryById($id);
			}
			if ($request->has('name')) {
				$professionCategory->setName($request->get('name'));
				$professionCategory->save();
			}
			$message     = 'Категорію успішно збережено';
			$messageType = 'success';
		} catch (Exception $e) {

			$message     = 'Категорію зберегти не вдалося';
			$messageType = 'error';
		}
		return response()->json([
			'ack'         => 'reload',
			'message'     => $message,
			'messageType' => $messageType,
		]);
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function categoryDelete(Request $request): JsonResponse
	{
		try {
			if ($request->get('id') !== -1) {
				$professionController = new ProfessionsModelController(new ProfessionsModelRepo());
				$professionCategory   = $professionController->findCategoryById($request->get('id'));
				$professionCategory->setDeletedAt(now('UTC'));
				$professionCategory->save();
				$message     = 'Категорію успішно видалено';
				$messageType = 'success';
			} else {
				$message     = 'Створення скасовано';
				$messageType = 'error';
			}
		} catch (Exception $e) {
			$message     = 'Категорію видалити не вдалося';
			$messageType = 'error';
		}
		return response()->json([
			'ack'         => 'reload',
			'message'     => $message,
			'messageType' => $messageType,
		]);
	}
}
