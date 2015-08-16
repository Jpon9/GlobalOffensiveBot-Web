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
		$sidebar = file_get_contents(config('app.botpath') . 'config/sidebar.txt');

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
			'sidebar' => 'required'
		]);

		$output = fopen(config('app.botpath') . 'config/sidebar.txt', 'w');
		flock($output, LOCK_EX);
		fwrite($output, $request->input('sidebar'));
		flock($output, LOCK_UN);
		fclose($output);

		$this->log('Sidebar', Auth::user()->username, 'Updated the sidebar.');

		return $this->index(true);
	}
}