<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Loggable;
use Illuminate\Http\Request;
use Auth;

class NoticesController extends Loggable {

	/**
	 * Show the notices page
	 *
	 * @return Response
	 */
	public function index($posted = false)
	{
		return view('notices');
	}

	/**
	 * Return the notices JSON file
	 */
	public function getNotices()
	{
		$notices = json_decode(file_get_contents(config('app.botpath') . 'config/notices.json'), true);

		return response()->json($notices);
	}

	/**
	 * Update the notices
	 */
	public function updateNotices(Request $request)
	{
		$oldNotices = json_decode(file_get_contents(config('app.botpath') . 'config/notices.json'), true);
		$notices = json_decode($request->input('notices'), true);
		$output = fopen(config('app.botpath') . 'config/notices.json', 'w');
		flock($output, LOCK_EX);
		fwrite($output, json_encode($this->mergeNotices($oldNotices, $notices), JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
		flock($output, LOCK_UN);
		fclose($output);

		$this->log('Notices', Auth::user()->username, "Updated the notices.");

		return response()->json(['status' => 'success']);
	}

	// Updates the current notices on the server with the new values from the webpanel.
	protected function mergeNotices($a, $b)
	{
		// Layer in the diffs from the webpanel
		foreach ($b as $bo) {
			$matchFound = false;
			var_dump($a);
			foreach ($a as $ai => $av) {
				if ($a[$ai]['unique_notice_id'] == $bo['unique_notice_id']) {
					$matchFound = true;
					foreach ($bo as $bp => $bv) {
						$a[$ai][$bp] = $bv;
					}
				}
			}
			if (!$matchFound) { array_push($a, $bo); }
		}
		// Make sure anything removed on the webpanel is removed in the bot
		foreach ($a as $ai => $av) {
			$found = false;
			foreach ($b as $bo) {
				if ($a[$ai]['unique_notice_id'] == $bo['unique_notice_id']) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				unset($a[$ai]);
			}
		}
		return array_values($a);
	}
}