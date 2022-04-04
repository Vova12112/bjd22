<?php

namespace App\ValuesObject;

use DB;

/**
 * Class AccidentType
 * @package App\ValuesObject
 */
class AccidentType
{

	/*** @return array */
	public static function getAccidentTypes(): array
	{
		return  DB::table('accident_types')->whereNull('deleted_at')->pluck('name','id')->toArray();
	}
}