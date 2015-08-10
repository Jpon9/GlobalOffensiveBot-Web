<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Loggable;
use Illuminate\Http\Request;
use Auth;

class SidebarController extends Loggable {

	/**
	 * Show the sidebar editing page
	 *
	 * @return Response
	 */
	public function index($posted = false)
	{
		$sidebar = json_decode(file_get_contents(config('app.botpath') . 'config/description.json'), true);

		$data = ['sidebar' => $sidebar];

		if ($posted) {
			$data['success_message'] = 'Success! The sidebar has been updated.';
		}

		return view('sidebar', $data);
	}

	/**
	 * Handle the data being posted
	 */
	public function updateSidebar(Request $request)
	{
		$this->validate($request, [
			'template' => 'required'
		]);

		$newSidebar = [ 'template' => '','chunks'=>[] ];
		$input = $request->all();
		foreach ($input as $name => $body) {
			if ($name == '_token') {
				continue;
			}
			if ($name == 'template') {
				$newSidebar['template'] = $body;
				continue;
			}
			array_push($newSidebar['chunks'], array("name" => $name, "body" => $body));
		}
		$output = fopen(config('app.botpath') . 'config/description.json', 'w');
		flock($output, LOCK_EX);
		fwrite($output, json_encode($newSidebar, JSON_NUMERIC_CHECK));
		flock($output, LOCK_UN);
		fclose($output);

		$this->log('Sidebar', Auth::user()->username, "Updated the sidebar.");

		return $this->index(true);
	}
}