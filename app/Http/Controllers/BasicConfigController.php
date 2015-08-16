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
		$conf = json_decode(file_get_contents(config('app.botpath') . 'config/settings.json'), true);

		$data = [
			'subreddit' => $conf['subreddit'],
			'update_interval_mins' => $conf['sidebar']['update_interval_mins'],
			'max_streams_shown' => $conf['sidebar']['livestream_feed']['max_streams_shown'],
			'max_games_shown' => $conf['sidebar']['matchticker']['max_games_shown'],
			'stream_thumbnail_css_name' => $conf['sidebar']['livestream_feed']['spritesheet_name'],
			'spotlight_rotation_timeout' => $conf['sidebar']['community_spotlight_interval_mins'],
			'num_of_headers' => $conf['sidebar']['num_of_headers'],
			'minify_stylesheet' => $conf['sidebar']['minify_stylesheet']
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
			'subreddit' => 'required',
			'update_interval_mins' => 'required',
			'max_streams_shown' => 'required',
			'max_games_shown' => 'required',
			'stream_thumbnail_css_name' => 'required',
			'spotlight_rotation_timeout' => 'required',
			'num_of_headers' => 'required'
		]);

		$conf = json_decode(file_get_contents(config('app.botpath') . 'config/settings.json'), true);

		$conf['subreddit'] = $request->input('subreddit');
		$conf['sidebar']['update_interval_mins'] = $request->input('update_interval_mins');
		$conf['sidebar']['livestream_feed']['max_streams_shown'] = $request->input('max_streams_shown');
		$conf['sidebar']['matchticker']['max_games_shown'] = $request->input('max_games_shown');
		$conf['sidebar']['livestream_feed']['spritesheet_name'] = $request->input('stream_thumbnail_css_name');
		$conf['sidebar']['community_spotlight_interval_mins'] = $request->input('spotlight_rotation_timeout');
		$conf['sidebar']['num_of_headers'] = $request->input('num_of_headers');
		$conf['sidebar']['minify_stylesheet'] = gettype($request->input('minify_stylesheet')) == "string";

		$output = fopen(config('app.botpath') . 'config/settings.json', 'w');
		flock($output, LOCK_EX);
		fwrite($output, json_encode($conf, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
		flock($output, LOCK_UN);
		fclose($output);

		$this->log('Basic Config', Auth::user()->username, "Updated the basic config.");

		return $this->index(true);
	}
}