<?php

namespace App\Http\Controllers;

use App\Models\Controllers\ProfessionsModelController;
use App\Models\Controllers\SegmentsModelController;
use App\Models\Controllers\WorkersModelController;
use App\Models\Repo\ProfessionsModelRepo;
use App\Models\Repo\SegmentsModelRepo;
use App\Models\Repo\WorkersModelRepo;
use App\Models\User;
use App\ValuesObject\Convertors\ConvertToGrid;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PaginatorController
 * @package App\Http\Controllers
 */
class PaginatorController extends Controller
{

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function workers(Request $request): JsonResponse
	{
		try {
			$currentPage       = (int) $request->get('current_page', 1);
			$pageSize          = (int) $request->get('page_size', 10);
			$sortField         = (string) $request->get('sort_field', 'last_name');
			$sortOrder         = (string) $request->get('sort_order', 'asc');
			$search            = (string) $request->get('search', '');
			$filters           = (array) $request->get('filters', []);
			$workersController = new WorkersModelController(new WorkersModelRepo());
			$workers           = $workersController->fetchPageWorkers($currentPage, $pageSize, $sortField, $sortOrder, $search, $filters);
			return response()->json(ConvertToGrid::workers($workers));
		} catch (Exception $e) {
			return response()->json([
				"from"  => 0,
				"to"    => 0,
				"total" => 0,
				"data"  => [
					[],
				],
			]);
		}
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function segments(Request $request): JsonResponse
	{
		try {
			$currentPage        = (int) $request->get('current_page', 1);
			$pageSize           = (int) $request->get('page_size', 10);
			$sortField          = (string) $request->get('sort_field', 'name');
			$sortOrder          = (string) $request->get('sort_order', 'asc');
			$search             = (string) $request->get('search', '');
			$segmentsController = new SegmentsModelController(new SegmentsModelRepo());
			$segments           = $segmentsController->fetchPageSegments($currentPage, $pageSize, $sortField, $sortOrder, $search);
			return response()->json(ConvertToGrid::segments($segments));
		} catch (Exception $e) {
			return response()->json([
				"from"  => 0,
				"to"    => 0,
				"total" => 0,
				"data"  => [
					[],
				],
			]);
		}
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function professions(Request $request): JsonResponse
	{
		try {
			$currentPage           = (int) $request->get('current_page', 1);
			$pageSize              = (int) $request->get('page_size', 10);
			$sortField             = (string) $request->get('sort_field', 'name');
			$sortOrder             = (string) $request->get('sort_order', 'asc');
			$search                = (string) $request->get('search', '');
			$professionsController = new ProfessionsModelController(new ProfessionsModelRepo());
			$professions           = $professionsController->fetchPageProfessions($currentPage, $pageSize, $sortField, $sortOrder, $search);
			return response()->json(ConvertToGrid::professions($professions));
		} catch (Exception $e) {
			return response()->json([
				"from"  => 0,
				"to"    => 0,
				"total" => 0,
				"data"  => [
					[],
				],
			]);
		}
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function categories(Request $request): JsonResponse
	{
		try {
			$currentPage           = (int) $request->get('current_page', 1);
			$pageSize              = (int) $request->get('page_size', 10);
			$sortField             = (string) $request->get('sort_field', 'name');
			$sortOrder             = (string) $request->get('sort_order', 'asc');
			$search                = (string) $request->get('search', '');
			$professionsController = new ProfessionsModelController(new ProfessionsModelRepo());
			$professions           = $professionsController->fetchPageProfessionCategories($currentPage, $pageSize, $sortField, $sortOrder, $search);
			return response()->json(ConvertToGrid::categories($professions));
		} catch (Exception $e) {
			return response()->json([
				"from"  => 0,
				"to"    => 0,
				"total" => 0,
				"data"  => [
					[],
				],
			]);
		}
	}
}
