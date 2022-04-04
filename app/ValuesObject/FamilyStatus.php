<?php

namespace App\ValuesObject;

/**
 * Class Genders
 * @package App\ValuesObject
 */
class FamilyStatus
{

	public const MARRIED = 1;

	public const NOT_MARRIED = 2;

	public const DIVORCED = 3;

	/*** @return array */
	public static function getMarryStatus(): array
	{
		return [
			self::MARRIED => 'Одружений/на',
			self::NOT_MARRIED    => 'Неодружений/на',
			self::DIVORCED  => 'Розлучений/на',
		];
	}
}