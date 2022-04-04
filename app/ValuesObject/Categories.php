<?php

namespace App\ValuesObject;

use DB;

/**
 * Class Genders
 * @package App\ValuesObject
 */
class Categories
{

	/*** @return array */
	public static function getCategories(): array
	{
		return  DB::table('profession_categories')->pluck('name','id')->toArray();;
	}
}