<?php

namespace App\Models;

/**
 * Class ProfessionCategory
 * @property string name
 * @package App\Models
 * @method static where(string $string, string $string1, $id)
 */
class ProfessionCategory extends AModel
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
