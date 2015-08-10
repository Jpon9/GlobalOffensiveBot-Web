<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Loggable;
use Illuminate\Http\Request;
use Auth;

class BasicConfigController extends Loggable {

	/**
	 * Show the basic config form
	 *
	 * @return Response
	 */
	public function index($posted = false)
	{
		$basicConfig = json_decode(file_get_contents(config('app.botpath') . 'config/settings.json'), true)['settings'];

		$data = [
			'target_subreddit' => isset($basicConfig['target_subreddit']) ? $basicConfig['target_subreddit'] : '',
			'update_timeout' => isset($basicConfig['update_timeout']) ? $basicConfig['update_timeout'] : '',
			'max_streams_shown' => isset($basicConfig['max_streams_shown']) ? $basicConfig['max_streams_shown'] : '',
			'max_games_shown' => isset($basicConfig['max_games_shown']) ? $basicConfig['max_games_shown'] : '',
			'stream_thumbnail_css_name' => isset($basicConfig['stream_thumbnail_css_name']) ? $basicConfig['stream_thumbnail_css_name'] : '',
			'spotlight_rotation_timeout' => isset($basicConfig['spotlight_rotation_timeout']) ? $basicConfig['spotlight_rotation_timeout'] : '',
			'num_of_headers' => isset($basicConfig['num_of_headers']) ? $basicConfig['num_of_headers'] : '',
			'google_api_key' => isset($basicConfig['google_api_key']) ? $basicConfig['google_api_key'] : '',
			'gosugamers_api_key' => isset($basicConfig['gosugamers_api_key']) ? $basicConfig['gosugamers_api_key'] : '',
			'steam_api_key' => isset($basicConfig['steam_api_key']) ? $basicConfig['steam_api_key'] : '',
			'minify_stylesheet' => $basicConfig['minify_stylesheet']
		];

		if ($posted) {
			$data['success_message'] = 'Success! The basic config has been updated.';
		}

		return view('basic-config', $data);
	}

	/**
	 * Handle the data being posted
	 */
	public function updateConfig(Request $request)
	{
		$this->validate($request, [
			'target_subreddit' => 'required',
			'update_timeout' => 'required',
			'max_streams_shown' => 'required',
			'max_games_shown' => 'required',
			'stream_thumbnail_css_name' => 'required',
			'spotlight_rotation_timeout' => 'required',
			'num_of_headers' => 'required',
			'google_api_key' => 'required',
			'gosugamers_api_key' => 'required',
			'steam_api_key' => 'required'
		]);

		$settings = [
			"settings" => [
				"target_subreddit" => $request->input('target_subreddit'),
				"update_timeout" => $request->input('update_timeout'),
				"max_streams_shown" => $request->input('max_streams_shown'),
				"max_games_shown" => $request->input('max_games_shown'),
				"stream_thumbnail_css_name" => $request->input('stream_thumbnail_css_name'),
				"spotlight_rotation_timeout" => $request->input('spotlight_rotation_timeout'),
				"num_of_headers" => $request->input('num_of_headers'),
				"google_api_key" => $request->input('google_api_key'),
				"gosugamers_api_key" => $request->input('gosugamers_api_key'),
				"steam_api_key" => $request->input('steam_api_key'),
				"minify_stylesheet" => gettype($request->input('minify_stylesheet')) == "string"
			]
		];
		$output = fopen(config('app.botpath') . 'config/settings.json', 'w');
		flock($output, LOCK_EX);
		fwrite($output, json_encode($settings, JSON_NUMERIC_CHECK));
		flock($output, LOCK_UN);
		fclose($output);

		$this->log('Basic Config', Auth::user()->username, "Updated the basic config.");

		return $this->index(true);
	}
}