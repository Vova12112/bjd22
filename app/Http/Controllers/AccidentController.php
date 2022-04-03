<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccidentController extends Controller
{
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
