<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class BotController extends Controller {

	/**
	 * Return the bot's status in JSON
	 */
	public function status()
	{
		$ret = [];
		$status = "indeterminate.";
		if (PHP_OS == 'WINNT') {
			$result = [];
			exec("tasklist /FI \"IMAGENAME eq python.exe\" /FO TABLE", $result);
			$ret = ["status" => count($result) > 1 ? "online" : "offline"];
		} else {
			$processStatus = explode("\n", shell_exec('ps -ef | grep python -'));
			$psLine = "";
			foreach ($processStatus as $ps) {
				if (strpos($ps, "main.py") !== false) {
					$psLine = $ps;
					break;
				}
			}
			$matches = [];
			preg_match("/(jpon9|jake|www-data) +(\d+) /", $psLine, $matches);
			$ret = ["status" => isset($matches[2]) ? "online" : "offline"];
		}

		return response()->json($ret);
	}

	/**
	 * Return the bot's metadata in JSON
	 */
	public function metadata()
	{
		$metadata = json_decode(file_get_contents(config('app.botpath') . 'cache/metadata.json'), true);

		return response()->json($metadata);
	}
}