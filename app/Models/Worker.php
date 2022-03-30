<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Worker
 * @property string|NULL first_name
 * @property string|NULL last_name
 * @property string|NULL sub_name
 * @property int         sex
 * @property bool        married
 * @property Carbon|NULL birth_at
 * @property int         structure_segment_id
 * @property int         profession_id
 * @property Carbon|NULL body_check_at
 * @property Carbon|NULL instructed_at
 * @property string|NULL description
 * @package App\Models
 * @method static where(string $string, string $string1, int $id)
 */
class Worker extends AModel
{
	/*** @var string[] */
	protected $dates = ['birth_at', 'body_check_at', 'instructed_at', 'created_at', 'updated_at'];

	/*** @return BelongsTo */
	public function structureSegment(): BelongsTo
	{
		return $this->belongsTo(StructureSegment::class);
	}

	/*** @return string */
	public function getFullName(): ?string
	{
		return sprintf('%s %s %s', $this->last_name, $this->first_name, $this->sub_name);
	}

	/*** @return BelongsTo */
	public function profession(): BelongsTo
	{
		return $this->belongsTo(Profession::class);
	}

	/*** @return string|NULL */
	public function getFirstName(): ?string
	{
		return $this->first_name;
	}

	/*** @param string|NULL $first_name */
	public function setFirstName(?string $first_name): void
	{
		$this->first_name = $first_name;
	}

	/*** @return string|NULL */
	public function getLastName(): ?string
	{
		return $this->last_name;
	}

	/*** @param string|NULL $last_name */
	public function setLastName(?string $last_name): void
	{
		$this->last_name = $last_name;
	}

	/*** @return string|NULL */
	public function getSubName(): ?string
	{
		return $this->sub_name;
	}

	/*** @param string|NULL $sub_name */
	public function setSubName(?string $sub_name): void
	{
		$this->sub_name = $sub_name;
	}

	/*** @return int */
	public function getSex(): int
	{
		return $this->sex;
	}

	/*** @param int $sex */
	public function setSex(int $sex): void
	{
		$this->sex = $sex;
	}

	/*** @return bool */
	public function isMarried(): bool
	{
		return $this->married;
	}

	/*** @param bool $married */
	public function setMarried(bool $married): void
	{
		$this->married = $married;
	}

	/*** @return Carbon|NULL */
	public function getBirthAt(): ?Carbon
	{
		return $this->birth_at;
	}

	/*** @param Carbon|NULL $birth_at */
	public function setBirthAt(?Carbon $birth_at): void
	{
		$this->birth_at = $birth_at;
	}

	/*** @return Carbon|NULL */
	public function getBodyCheckAt(): ?Carbon
	{
		return $this->body_check_at;
	}

	/*** @param Carbon|NULL $body_check_at */
	public function setBodyCheckAt(?Carbon $body_check_at): void
	{
		$this->body_check_at = $body_check_at;
	}

	/*** @return Carbon|NULL */
	public function getInstructedAt(): ?Carbon
	{
		return $this->instructed_at;
	}

	/*** @param Carbon|NULL $instructed_at */
	public function setInstructedAt(?Carbon $instructed_at): void
	{
		$this->instructed_at = $instructed_at;
	}

	/*** @return string|NULL */
	public function getDescription(): ?string
	{
		return $this->description;
	}

	/*** @param string|NULL $description */
	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

}
