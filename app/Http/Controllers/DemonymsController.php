<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Loggable;
use Illuminate\Http\Request;
use Auth;

class DemonymsController extends Loggable {

	/**
	 * Show the demonyms page
	 *
	 * @return Response
	 */
	public function index($posted = false)
	{
		$demonyms = json_decode(file_get_contents(config('app.botpath') . 'config/stylesheet/random_demonyms.json'), true)['demonyms'];

		$data = ['demonyms' => $demonyms];

		if ($posted) {
			$data['success_message'] = 'Success! The demonyms have been updated.';
		}

		return view('demonyms', $data);
	}

	/**
	 * Handle the data being posted
	 */
	public function updateDemonyms(Request $request)
	{
		$input = $request->all();

		$demonyms = ['demonyms' => []];

		foreach ($input as $name => $demonym) {
			if ($name == "_token") {
				continue;
			}

			array_push($demonyms['demonyms'], ["subscribers" => $demonym[0], "online" => $demonym[1]]);
		}

		$output = fopen(config('app.botpath') . 'config/stylesheet/random_demonyms.json', 'w');
		flock($output, LOCK_EX);
		fwrite($output, json_encode($demonyms, JSON_NUMERIC_CHECK));
		flock($output, LOCK_UN);
		fclose($output);

		$this->log('Demonyms', Auth::user()->username, "Updated the demonyms.");

		return $this->index(true);
	}
}