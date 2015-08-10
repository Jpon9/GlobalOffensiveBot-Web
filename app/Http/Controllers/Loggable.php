<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\LogMessage;

class Loggable extends Controller {

	/**
	 * Logs an action in the database.
	 *
	 * Returns nothing.
	 */
	public function log($page, $user, $note)
	{
		$logMessage = new LogMessage;
		$logMessage->page = $page;
		$logMessage->user = $user;
		$logMessage->note = $note;
		$logMessage->save();
	}
}