<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Loggable;
use Illuminate\Http\Request;
use Auth;

class StylesheetController extends Loggable {

	/**
	 * Show the stylesheet editing page
	 *
	 * @return Response
	 */
	public function index($posted = false)
	{
		$stylesheet = file_get_contents(config('app.botpath') . 'config/stylesheet.txt');

		$data = ['stylesheet' => $stylesheet];

		if ($posted) {
			$data['success_message'] = 'Success! The stylesheet has been updated.';
		}

		return view('stylesheet', $data);
	}

	/**
	 * Handle the data being posted
	 */
	public function updateStylesheet(Request $request)
	{
		$this->validate($request, [
			'stylesheet' => 'required|max:100000'
		]);

		$output = fopen(config('app.botpath') . 'config/stylesheet.txt', 'w');
		flock($output, LOCK_EX);
		fwrite($output, $request->input('stylesheet'));
		flock($output, LOCK_UN);
		fclose($output);

		$this->log('Stylesheet', Auth::user()->username, "Updated the stylesheet.");

		return $this->index(true);
	}
}