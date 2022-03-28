<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AModel
 * @property integer     id
 * @property Carbon      created_at
 * @property Carbon      updated_at
 * @property Carbon|NULL deleted_at
 * @package App\Models\Abstracts
 */
abstract class AModel extends Model
{

	use SoftDeletes;

	/*** @var string[] */
	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

	/*** @return int */
	public function getId(): int
	{
		return $this->id;
	}

	/*** @return Carbon */
	public function getCreatedAt(): Carbon
	{
		return $this->created_at;
	}

	/*** @return Carbon */
	public function getUpdatedAt(): Carbon
	{
		return $this->updated_at;
	}

	/*** @return Carbon|NULL */
	public function getDeletedAt(): ?Carbon
	{
		return $this->deleted_at;
	}

	/*** @param Carbon|NULL $deletedAt */
	public function setDeletedAt(?Carbon $deletedAt): void
	{
		$this->deleted_at = $deletedAt;
	}

	/*** @return bool */
	public function isDeleted(): bool
	{
		return $this->getDeletedAt() !== NULL;
	}

}