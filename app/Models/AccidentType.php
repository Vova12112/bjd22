<?php

namespace App\Models;

/**
 * Class AccidentType
 * @property string name
 * @package App\Models
 * @method static where(string $string, string $string1, int $id)
 */
class AccidentType extends AModel
{
	/*** @return string */
	public function getName(): string
	{
		return $this->name;
	}

	/*** @param string $name */
	public function setName(string $name): void
	{
		$this->name = $name;
	}
}
