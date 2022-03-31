<?php

namespace App\ValuesObject\Convertors;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ConvertToGrid
 * @package App\ValuesObject\Convertors
 */
class ConvertToGrid
{

	/**
	 * @param LengthAwarePaginator $workers
	 * @return array
	 */
	public static function workers(LengthAwarePaginator $workers): array
	{
		$data = self::getGeneralData($workers);
		try {
			foreach ($workers->items() as $key => $item) {
				$data['data'][$key] = [
					'id'                 => $item->id,
					'first_name'         => htmlspecialchars($item->first_name),
					'last_name'          => htmlspecialchars($item->last_name),
					'sub_name'           => htmlspecialchars($item->sub_name),
					'structure_segments' => htmlspecialchars($item->structure_segments),
					'profession'         => htmlspecialchars($item->profession),
				];
			}
		} catch (Exception $e) {
		}
		return $data;
	}

	/**
	 * @param LengthAwarePaginator $segments
	 * @return array
	 */
	public static function segments(LengthAwarePaginator $segments): array
	{
		$data = self::getGeneralData($segments);
		try {
			foreach ($segments->items() as $key => $item) {
				$data['data'][$key] = [
					'id'                 => $item->id,
					'name'         => htmlspecialchars($item->name),
				];
			}
		} catch (Exception $e) {
		}
		return $data;
	}

	/**
	 * @param LengthAwarePaginator $data
	 * @return array
	 */
	private static function getGeneralData(LengthAwarePaginator $data): array
	{
		return [
			'from'  => $data->firstItem(),
			'to'    => $data->lastItem(),
			'total' => $data->total(),
			'data'  => [],
		];
	}
}