<?php

namespace App\Models;

/**
 * Class ProfessionCategory
 * @property string name
 * @package App\Models
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
