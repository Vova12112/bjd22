<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Organization
 * @property string name
 * @property string address
 * @package App\Models
 * @method static where(string $string, string $string1, string $string2)
 */
class Organization extends AModel
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

	/*** @return string */
	public function getAddress(): string
	{
		return $this->address;
	}

	/*** @param string $address */
	public function setAddress(string $address): void
	{
		$this->address = $address;
	}

}
