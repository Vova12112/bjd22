<?php

namespace App\Http\Controllers;

use App\Models\Controllers\SegmentsModelController;
use App\Models\Repo\SegmentsModelRepo;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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
		return view('pages.organization');
	}

	/*** @return Application|Factory|View */
	public function organizationStructure()
	{
		$buttons = [
		[
			'label' => '+',
			'class' => 'js-create',
			'alt' => 'Новий відділ'
		],
		[
			'label' => '<',
			'class' => 'js-back nav-link',
			'alt' => 'Повернутись назад',
			'args' => 'data-route="' . route('home') . '"',
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
			'ack'=>'redirect',
			'url'=> $request->has('segment_id') ? route('segment.details', ['id' => $request->get('segment_id')]) : '/'
		]);
	}

	public function segmentDetails(string $id)
	{
		$segmentController = new SegmentsModelController(new SegmentsModelRepo());
		$segment = $segmentController->findById((int)$id);
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create',
				'alt' => 'Новий відділ'
			],
			[
				'label' => 'х',
				'class' => 'js-delete',
				'alt' => 'Видалити відділ'
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt' => 'Повернутись назад',
				'args' => 'data-route="' . route('organization.segments') . '"',
			],
		];
		return view('pages.structure_segments_details', compact('segment', 'buttons'));
	}
}
