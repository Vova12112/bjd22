<?php

namespace App\Http\Controllers;

use App\Models\Controllers\ProfessionsModelController;
use App\Models\ProfessionCategory;
use App\Models\Repo\ProfessionsModelRepo;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
	/*** @return Application|Factory|View */
	public function professions()
	{
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create',
				'alt'   => 'Нова посада',
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

	public function professionDetails(string $id)
	{
		$professionController = new ProfessionsModelController(new ProfessionsModelRepo());
		$profession           = $professionController->findById((int) $id);
		$buttons              = [
			[
				'label' => '+',
				'class' => 'js-create',
				'alt'   => 'Нова посада',
			],
			[
				'label' => 'х',
				'class' => 'js-delete',
				'alt'   => 'Видалити посаду',
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt'   => 'Повернутись назад',
				'args'  => 'data-route="' . route('professions') . '"',
			],
		];
		return view('pages.professions_details', compact('profession', 'buttons'));
	}

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
			return response()->json([
				'ack' => 'reload',
			]);
		} catch (Exception $e) {
			return response()->json([
				'ack' => 'reload',
			]);
		}
	}

	public function categoryDelete(Request $request): JsonResponse
	{
		try {
			if ($request->get('id') !== -1) {
				$professionController = new ProfessionsModelController(new ProfessionsModelRepo());
				$professionCategory   = $professionController->findCategoryById($request->get('id'));
				$professionCategory->setDeletedAt(now('UTC'));
				$professionCategory->save();
			}
			return response()->json([
				'ack' => 'reload',
			]);
		} catch (Exception $e) {
			return response()->json([
				'ack' => 'reload',
			]);
		}
	}
}
