<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WordController extends Controller
{
	public function create()
	{
		return view('createdocument');
	}

	/**
	 * @param Request $request
	 * @return BinaryFileResponse
	 * @throws Exception
	 */
//	Пробник для запросов
	public function store(Request $request): BinaryFileResponse
	{
		$phpWord = new PhpWord();
		$section = $phpWord->addSection();
		$date = now('UTC');
		$text = $section->addText($request->get('name'));
		$text = $section->addText($request->get('email'));
		$text = $section->addText($request->get('number'),array('name'=>'Arial','size' => 20,'bold' => true));
		$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('DocumentBase/'. $date . '.docx');
		return response()->download(public_path('DocumentBase/'. $date . '.docx'));
	}

	/**
	 * @param string      $text
	 * @param string|null $tag
	 * @param string|null $type
	 * @return BinaryFileResponse
	 * @throws Exception
	 */
	// Запись в файл и открытие
	public function writeInDocOpen(string $text,?string $tag = '', ?string $type = 'doc'): BinaryFileResponse
	{
		$phpWord = new PhpWord();
		$section = $phpWord->addSection();
		$date = date('Ymd');
		$text = $section->addText($text);
		$path = $tag . $date;
		switch ($type){
			default:
				$objWriter = IOFactory::createWriter($phpWord);
				$objWriter->save($path . '.docx');
				return response()->download(public_path($path . '.docx'));
			case 'odt':
				$objWriter = IOFactory::createWriter($phpWord, 'ODText');
				$objWriter->save($path . '.odt');
				return response()->download(public_path($path  . '.odt'));
			case 'html':
				$objWriter = IOFactory::createWriter($phpWord, 'HTML');
				$objWriter->save($path . '.html');
				return response()->download(public_path($path  . '.html'));
		}
	}

	// Запись в файл
	/**
	 * @param string      $text
	 * @param string|null $tag
	 * @param string|null $type
	 * @return JsonResponse
	 * @throws Exception
	 */
	public function writeInDoc(string $text, ?string $tag = '', ?string $type = 'doc'): JsonResponse
	{
		$phpWord = new PhpWord();
		$section = $phpWord->addSection();
		$date = date('Ymd');
		$text = $section->addText($text);
		$path = $tag . $date;
		switch ($type){
			default:
				$objWriter = IOFactory::createWriter($phpWord);
				$objWriter->save($path . '.docx');
				break;
			case 'odt':
				$objWriter = IOFactory::createWriter($phpWord, 'ODText');
				$objWriter->save($path . '.odt');
				break;
			case 'html':
				$objWriter = IOFactory::createWriter($phpWord, 'HTML');
				$objWriter->save($path . '.html');
				break;
		}
		return response()->json(['ack' => 'success', 'message' => 'Document has been created']);
	}


	// Редактирование - непроверял
	/**
	 * @throws CopyFileException
	 * @throws CreateTemporaryFileException
	 * @throws Exception
	 */
	public function changeDoc(string $from, string $to, string $pathToFile): JsonResponse
	{
		$template = new TemplateProcessor($pathToFile);
		$template->setValue($from, $to);
		$pathToNewFile = 'changed' . $pathToFile;
		$template->saveAs($pathToNewFile);
		return response()->json(['ack' => 'success', 'message' => 'Document has been changed']);
	}

	//Удаление - непроверял
	/**
	 * @param string $pathToFile
	 * @return JsonResponse
	 */
	public function deleteDoc(string $pathToFile): JsonResponse
	{
		unlink($pathToFile);
		return response()->json(['ack' => 'success', 'message' => 'Document has been deleted']);
	}
}
