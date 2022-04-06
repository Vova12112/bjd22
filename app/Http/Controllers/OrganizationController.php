<?php

namespace App\Http\Controllers;

use App\Models\Controllers\OrganizationsModelController;
use App\Models\Controllers\SegmentsModelController;
use App\Models\Profession;
use App\Models\Repo\OrganizationsModelRepo;
use App\Models\Repo\SegmentsModelRepo;
use App\Models\StructureSegment;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class OrganizationController
 * @package App\Http\Controllers
 */
class OrganizationController extends Controller
{
	/*** @return Application|Factory|View */
	public function organization()
	{
		try {
			$organizationController = new OrganizationsModelController(new OrganizationsModelRepo());
			$organization           = $organizationController->getOrganization();
		} catch (Exception $e) {
			$organization = NULL;
		}
		return view('pages.organization', compact('organization'));
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function save(Request $request): JsonResponse
	{
		try {

			$organizationController = new OrganizationsModelController(new OrganizationsModelRepo());
			$organization           = $organizationController->getOrganization();
			if ($request->has('name')) {
				$organization->setName($request->get('name'));
			}
			if ($request->has('address')) {
				$organization->setAddress($request->get('address'));
			}
			$organization->save();
			$message = 'Дані про підприємство успішно збережено';
			$messageType = 'success';
		} catch (Exception $e) {
			$message = 'Дані про підприємство не збережено';
			$messageType = 'error';
		}
		return response()->json([
			'ack'         => 'reload',
			'message'     => $message,
			'messageType' => $messageType,
		]);
	}

	/**
	 * @param int     $id
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function segmentSave(int $id, Request $request): RedirectResponse
	{
		try {
			if ($id === -1) {
				$segment = new StructureSegment();
			} else {
				$segmentController = new SegmentsModelController(new SegmentsModelRepo());
				$segment           = $segmentController->findById($id);
			}
			if ($request->has('name')) {
				$segment->setName($request->get('name'));
				$segment->save();
			}
			$message = 'Відділ успішно збережено';
			$messageType = 'success';
		} catch (Exception $e) {
			$message     = 'Відділ не збережено';
			$messageType = 'error';
		}
		return response()->redirectToRoute('organization.segments', ['message' => $message, 'messageType' => $messageType]);
	}

	/*** @return Application|Factory|View */
	public function organizationStructure()
	{
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt'   => 'Новий відділ',
				'args'  => 'data-route="' . route('segment.details', ['id' => -1]) . '"',
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt'   => 'Повернутись назад',
				'args'  => 'data-route="' . route('home') . '"',
			],
		];
		return view('pages.structure_segments', ['search' => '', 'buttons' => $buttons]);
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function redirect(Request $request): JsonResponse
	{
		return response()->json([
			'ack' => 'redirect',
			'url' => $request->has('segment_id') ? route('segment.details', ['id' => $request->get('segment_id')]) : '/',
		]);
	}

	/**
	 * @param int $id
	 * @return Application|Factory|View
	 */
	public function segmentDetails(int $id)
	{
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt'   => 'Новий відділ',
				'args'  => 'data-route="' . route('segment.details', ['id' => -1]) . '"',
			],
			[
				'label' => 'х',
				'class' => 'js-delete nav-link',
				'alt'   => 'Видалити відділ',
				'args'  => 'data-route="' . route('segment.delete', ['id' => $id]) . '" data-method="POST"',
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt'   => 'Повернутись назад',
				'args'  => 'data-route="' . route('organization.segments') . '"',
			],
		];
		$params  = [
			'buttons' => $buttons,
		];
		if ($id !== -1) {
			$segmentController = new SegmentsModelController(new SegmentsModelRepo());
			$segment           = $segmentController->findById((int) $id);
			$params['segment'] = $segment;
		}
		return view('pages.structure_segments_details', $params);
	}

	/**
	 * @param int $id
	 * @return JsonResponse
	 */
	public function segmentDelete(int $id): JsonResponse
	{
		try {
			if ($id !== -1) {
				$segmentController = new SegmentsModelController(new SegmentsModelRepo());
				$segment           = $segmentController->findById($id);
				$segment->setDeletedAt(now('UTC'));
				$segment->save();
			}
			$message = 'Відділ успішно видалено';
			$messageType = 'success';
		} catch (Exception $e) {
			$message     = 'Відділ видалити не вдалося';
			$messageType = 'error';
		}
		return response()->json([
			'ack' => 'redirect',
			'message' => $message,
			'messageType' => $messageType,
			'url' => route('organization.segments'),
		]);
	}
}
