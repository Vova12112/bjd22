<?php

namespace App\ValuesObject;

/**
 * Class Genders
 * @package App\ValuesObject
 */
class Genders
{
	public const UNKNOWN = 0;

	public const MALE = 1;

	public const FEMALE = 2;

	/*** @return array */
	public static function getSex(): array
	{
		return [
			self::UNKNOWN => 'Невідомо',
			self::MALE    => 'Чоловік',
			self::FEMALE  => 'Жінка',
		];
	}
}