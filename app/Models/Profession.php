<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Profession
 * @property string name
 * @property int    profession_category_id
 * @package App\Models
 * @method static where(string $string, string $string1, int $id)
 */
class Profession extends AModel
{
	/*** @return BelongsTo */
	public function professionCategory(): BelongsTo
	{
		return $this->belongsTo(ProfessionCategory::class);
	}

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

	/*** @return int */
	public function getProfessionCategoryId(): int
	{
		return $this->profession_category_id;
	}

	/*** @param int $profession_category_id */
	public function setProfessionCategoryId(int $profession_category_id): void
	{
		$this->profession_category_id = $profession_category_id;
	}


}
