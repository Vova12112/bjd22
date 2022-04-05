<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Trait ExceptionResponse
 * @package App\Traits
 */
trait ExceptionResponse
{

	/**
	 * @param Exception $e
	 * @return RedirectResponse
	 */
	public function backErrorWith(Exception $e): RedirectResponse
	{
		return back()->with('Помилочка =(', $e->getMessage());
	}

	/**
	 * @param Exception $e
	 * @param string    $action
	 * @param string    $details
	 * @return JsonResponse
	 */
	public function ajaxFail(Exception $e, string $action, string $details): JsonResponse
	{
		$message = 'Ви спробували зробити ' . $action . ' бо ' . $details . PHP_EOL . $e->getMessage();
		return response()->json(['ack' => 'fail', 'message' => $message]);
	}

	/**
	 * @param Request   $request
	 * @param Exception $e
	 * @param string    $action
	 * @return JsonResponse
	 */
	public function ajaxException(Request $request, Exception $e, string $action): JsonResponse
	{
		try {
			$filters = json_encode($request->get('filters', []), JSON_THROW_ON_ERROR);
		} catch (Exception $e) {
			$filters = '[]';
		}
		return $this->ajaxFail(
			$e,
			$action,
			sprintf(
				'Current Page: %s%s Page Size: %s%s Sort Field: %s%s Sort Order: %s%s Filters: %s%s Search: %s%s',
				$request->get('current_page', 1),
				PHP_EOL,
				$request->get('page_size', 10),
				PHP_EOL,
				$request->get('sort_field'),
				PHP_EOL,
				$request->get('sort_order'),
				PHP_EOL,
				$filters,
				PHP_EOL,
				$request->get('search', ''),
				PHP_EOL
			)
		);
	}

	/*** @return JsonResponse */
	public function getPageResponse(): JsonResponse
	{
		return response()->json(['from' => 1, 'to' => 10, 'total' => 0, 'data' => [],]);
	}

}