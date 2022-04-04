<?php

namespace App\ValuesObject;

use DB;

/**
 * Class Worker
 * @package App\ValuesObject
 */
class Worker
{

	/*** @return array */
	public static function getWorkers(): array
	{
		return  DB::table('workers as t1')
			->select(DB::raw("CONCAT(t1.last_name, ' ',t1.first_name, ' ',t1.sub_name) as 'full_name', t1.id"))
			->whereNull('t1.fired_at')
			->pluck('full_name','id')
			->toArray();
	}
}