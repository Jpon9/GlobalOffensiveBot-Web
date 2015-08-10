<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Loggable;
use Illuminate\Http\Request;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ModmailController extends Loggable {

	/**
	 * Show the modmail query page
	 *
	 * @return Response
	 */
	public function index()
	{
		$db = DB::connection('mongodb');
		$modmail = $db->collection('modmail');

		$oldest = $modmail->orderBy('created_at', 'asc')->take(1)->get();

		return view('log.modmail.search', ['oldestModmailTime' => $oldest[0]['created_at']]);
	}

	/**
	 * Shows a listing of modmails based on the given criteria
	 */
	public function listing(Request $request)
	{
		$input = [];
		if ($request->session()->has('input') && count($request->all()) < 2) {
			$input = $request->session()->get('input');
		} else {
			$input = $request->all();
			$request->session()->put('input', $input);
		}

		$input['startdate'] = strtotime($input['startdate']);
		$input['enddate'] = strtotime($input['enddate']) + 60 * 60 * 24;
		$input['order'] = intval($input['order']);

		$db = DB::connection('mongodb');
		$modmail = $db->collection('modmail');

		if ($input['subject'] != '') {
			$modmail->orWhere('subject', 'like', '%' . $input['subject'] . '%')
					->orWhere('replies.subject', 'like', '%' . $input['subject'] . '%');
		}
		if ($input['body'] != '') {
			$modmail->orWhere('body', 'like', '%' . $input['body'] . '%')
					->orWhere('replies.body', 'like', '%' . $input['body'] . '%');
		}
		if ($input['author'] != '') {
			$modmail->orWhere('author', $input['author'])
					->orWhere('replies.author', $input['author']);
		}

		$results = $modmail
						->where('created_at', '>', $input['startdate'])
						->where('created_at', '<', $input['enddate'])
						->orderBy('created_at', ($input['order'] == 1 ? 'asc' : 'desc'))
						->take(2000)
						->paginate(50);

		$filters = [
			'start' => number_format($results->perPage() * ($results->currentPage() - 1) + 1),
			'end' => number_format($results->perPage() * $results->currentPage()),
			'total' => number_format($results->total()),
			'from' => strftime('%d %B %Y', $input['startdate']),
			'to' => strftime('%d %B %Y', $input['enddate']),
			'author' => $input['author'] != '' ? $input['author'] : 'anyone',
			'subject' => $input['subject'] != '' ? $input['subject'] : 'anything',
			'body' => $input['body'] != '' ? $input['body'] : 'anything'
		];

		$data = [
			'results' => $results,
			'filters' => $filters
		];

		return view('log.modmail.listing', $data);
	}
}