<?php

namespace App\ValuesObject;

use Carbon\Carbon;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class Genders
 * @package App\ValuesObject
 */
class TemplateWriter
{

	private string $templateName;
	private string $fileName;
	private array  $params;

	/**
	 * @param string $fileName
	 * @param array  $params
	 */
	public function __construct(string $templateName, string $fileName, array $params)
	{
		$this->templateName = $templateName;
		$this->fileName     = $fileName;
		$this->params       = $params;
	}

	/**
	 * @throws Exception
	 */
	public function save(?string $type = '.docx'): BinaryFileResponse
	{
		$phpWord      = new PhpWord();
		$templateName = $this->templateName;
		$section      = $phpWord->loadTemplate(public_path() . Path::UPLOAD . $templateName . $type);
		foreach ($this->params as $key => $param) {
			$section->setValue(sprintf('%s', $key), $param);
		}
		try {
			$section->saveAs(public_path() . Path::UPLOAD . $this->fileName . $type);
		} catch (Exception $e) {
		}
		return response()->download(public_path() . Path::UPLOAD . $this->fileName . $type);
	}

}