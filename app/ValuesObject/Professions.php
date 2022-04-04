<?php

namespace App\ValuesObject;

use DB;

/**
 * Class Genders
 * @package App\ValuesObject
 */
class Professions
{

	/*** @return array */
	public static function getCategories(): array
	{
		return  DB::table('professions')->whereNull('deleted_at')->pluck('name','id')->toArray();;
	}
}