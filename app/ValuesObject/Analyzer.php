<?php

namespace App\ValuesObject;

use App\Models\Repo\AnalizerRepo;
use Carbon\Carbon;

/**
 * Class Analizer
 * @package App\ValuesObject
 */
class Analyzer
{

	/**
	 * @param Carbon|null $from
	 * @param Carbon|null $to
	 * @return array
	 */
	public function cIndustrialAccident(?Carbon $from = NULL, ?Carbon $to = NULL): array
	{
		$analyzeController = new AnalizerRepo();
		$all = $analyzeController->counter(null, $from, $to);
		$arr = $analyzeController->getAllAccidents();
		$i = 0;
		foreach ($arr as $value=>$argv){
			$arr[$i] = $analyzeController->counter($argv)/$all*100;
			$i++;
		}
		return $arr;
	}

	/**
	 * @param string|null $accident
	 * @param Carbon|null $from
	 * @param Carbon|null $to
	 * @return array
	 */
	public function cAccidentsByOld(?string $accident = NULL, ?Carbon $from = NULL, ?Carbon $to = NULL): array
	{
		$analyzeController = new AnalizerRepo();
			$all[0] = $analyzeController->counterForWorker($accident,$from,$to,null, null, 18);
			$all[1] = $analyzeController->counterForWorker($accident,$from,$to,null, 18, 25);
			$all[2] = $analyzeController->counterForWorker($accident,$from,$to,null, 26, 30);
			$all[3] = $analyzeController->counterForWorker($accident,$from,$to,null, 31, 40);
			$all[4] = $analyzeController->counterForWorker($accident,$from,$to,null, 41, 50);
			$all[5] = $analyzeController->counterForWorker($accident,$from,$to,null, 50, null);
		return $all;
	}

	/**
	 * @param string|null $accident
	 * @param Carbon|null $from
	 * @param Carbon|null $to
	 * @return array
	 */
	public function cAccidentBySex (?string $accident, ?Carbon $from = NULL, ?Carbon $to = NULL): array
	{
		$analyzeController = new AnalizerRepo();
		$all[0] = $analyzeController->counterForWorker($accident,$from,$to,1 );
		$all[1] = $analyzeController->counterForWorker($accident,$from,$to,2 );
		return $all;
	}

	/**
	 * @param             $profession_id
	 * @param string|null $accident
	 * @param Carbon|null $from
	 * @param Carbon|null $to
	 * @return int
	 */
	public function cAccidentByCategory ($profession_id, ?string $accident, ?Carbon $from = NULL, ?Carbon $to = NULL): int
	{
		$analyzeController = new AnalizerRepo();
		return $analyzeController->counterForWorker($accident,$from,$to,-1,-1,-1,$profession_id);
	}
}