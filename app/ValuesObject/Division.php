<?php

namespace App\ValuesObject;

use DB;

/**
 * Class Genders
 * @package App\ValuesObject
 */
class Division
{

	/*** @return array */
	public static function getDivisions(): array
	{
		return  DB::table('structure_segments')->whereNull('deleted_at')->pluck('name','id')->toArray();;
	}
}