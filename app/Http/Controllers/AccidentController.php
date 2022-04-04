<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccidentController extends Controller
{


	public function accidentWorkers()
	{
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt' => 'Новий інцидент',
				'args' => 'data-route=""'
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt' => 'Повернутись назад',
				'args' => 'data-route="' . route('home') . '"',
			],
		];
		return view('pages.workers_accidents', ['search' => '', 'buttons' => $buttons]);
	}

	public function show()
	{
		$buttons = [
			[
				'label' => '+',
				'class' => 'js-create nav-link',
				'alt' => 'Новий вид інциденту',
				'args' => 'data-route=""'
			],
			[
				'label' => '<',
				'class' => 'js-back nav-link',
				'alt' => 'Повернутись назад',
				'args' => 'data-route="' . route('home') . '"',
			],
		];
		return view('pages.accidents', ['search' => '', 'buttons' => $buttons]);
	}
}
