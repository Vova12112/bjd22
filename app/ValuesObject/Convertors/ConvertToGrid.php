<?php

namespace App\ValuesObject\Convertors;

use App\Models\WorkerAccident;
use Carbon\Carbon;
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
					'id'   => $item->id,
					'name' => htmlspecialchars($item->name),
				];
			}
		} catch (Exception $e) {
		}
		return $data;
	}

	/**
	 * @param LengthAwarePaginator $professions
	 * @return array
	 */
	public static function professions(LengthAwarePaginator $professions): array
	{
		$data = self::getGeneralData($professions);
		try {
			foreach ($professions->items() as $key => $item) {
				$data['data'][$key] = [
					'id'   => $item->id,
					'name' => htmlspecialchars($item->profession_name),
				];
			}
		} catch (Exception $e) {
		}
		return $data;
	}

	public static function categories(\Illuminate\Contracts\Pagination\LengthAwarePaginator $categories): array
	{
		$data = self::getGeneralData($categories);
		try {
			foreach ($categories->items() as $key => $item) {
				$data['data'][$key] = [
					'id'   => $item->id,
					'name' => htmlspecialchars($item->name),
				];
			}
		} catch (Exception $e) {
		}
		return $data;
	}

	public static function accidents(LengthAwarePaginator $accidents): array
	{
		$data = self::getGeneralData($accidents);
		try {
			foreach ($accidents->items() as $key => $item) {
				$data['data'][$key] = [
					'id'   => $item->id,
					'name' => htmlspecialchars($item->name),
					'created_at' => htmlspecialchars($item->created_at),
					'updated_at' => htmlspecialchars($item->updated_at),
				];
			}
		} catch (Exception $e) {
		}
		return $data;
	}

	public static function workersAccident(LengthAwarePaginator $accidents)
	{
		$total = $accidents->total();
		$totalG = WorkerAccident::all()->count();
		$data = [
			'from'  => $accidents->firstItem(),
			'to'    => $accidents->lastItem(),
			'total' => $accidents->total() . ' (' . round((int)$total * 100 / (int)$totalG, 2) . '%)',
			'data'  => [],
		];
		try {
			foreach ($accidents->items() as $key => $item) {
				$data['data'][$key] = [
					'id'            => $item->id,
					'full_name'     => sprintf(
						'%s%s%s',
						htmlspecialchars($item->last_name) . ' ',
						htmlspecialchars($item->first_name) . ' ',
						htmlspecialchars($item->sub_name) . ' ',
					),
					'accident_name' => htmlspecialchars($item->accident_name),
					'accident_at'   => Carbon::parse($item->accident_at)->format('d.m.y h:i'),
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