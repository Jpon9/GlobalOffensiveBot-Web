<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Loggable;
use Illuminate\Http\Request;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ModlogController extends Loggable {

	protected $labels = [
		"banuser" => "Ban User",
		"unbanuser" => "Unban User",
		"removelink" => "Remove Post",
		"approvelink" => "Approve Post",
		"removecomment" => "Remove Comment",
		"approvecomment" => "Approve Comment",
		"addmoderator" => "Add Mod",
		"invitemoderator" => "Remove Mod",
		"uninvitemoderator" => "Uninvite Mod",
		"acceptmoderatorinvite" => "Accept Mod Invite",
		"removemoderator" => "Remove Mod",
		"addcontributor" => "Add Contributor",
		"removecontributor" => "Remove Contributor",
		"editsettings" => "Edit Settings",
		"editflair" => "Edit Flair",
		"distinguish" => "Distinguish",
		"marknsfw" => "Mark NSFW",
		"wikibanned" => "Wiki Ban",
		"wikicontributor" => "Add Wiki Contributor",
		"wikiunbanned" => "Wiki Unban",
		"wikipagelisted" => "Change Wiki Listing",
		"removewikicontributor" => "Remove Wiki Contributor",
		"wikirevise" => "Edit Wiki Page",
		"wikipermlevel" => "Edit Wiki Perms",
		"ignorereports" => "Ignore Reports",
		"unignorereports" => "Unignore Reports",
		"setpermissions" => "Change Mod Perms",
		"setsuggestedsort" => "Set Suggested Sort",
		"sticky" => "Sticky Thread",
		"unsticky" => "Unsticky Thread",
		"total" => "Total",
		"percent" => "Percent"
	];

	protected $shownActions = [
		"removelink" => "Remove Link",
		"approvelink" => "Approve Link",
		"removecomment" => "Remove Comment",
		"approvecomment" => "Approve Comment",
		"editflair" => "Edit Flair",
		"banuser" => "Ban User",
		"distinguish" => "Distinguish"
	];

	public function index() {
		$db = DB::connection('mongodb');
		$modlog = $db->collection('modlog');

		$mods = $modlog->select('mod')->distinct()->get();
		natcasesort($mods);

		$data = [
			'mods' => $mods,
			'actions' => $this->labels,
			'defaultActions' => $this->shownActions
		];

		return view('log.mod.search', $data);
	}

	/**
	 * Show the results of the queried modlog entries
	 */
	public function query(Request $request)
	{
		$input = [];
		if ($request->session()->has('input') && count($request->all()) < 2) {
			$input = $request->session()->get('input');
		} else {
			$input = $request->all();
			$request->session()->put('input', $input);
		}

		$input['mods'] = array_values($input['mods']);
		$input['actions'] = array_values($input['actions']);
		$input['startdate'] = strtotime($input['startdate']);
		$input['enddate'] = strtotime($input['enddate']) + 60 * 60 * 24;
		$input['order'] = intval($input['order']);
		$input['detail'] = $input['detail'] != '' ? "\"" . $input['detail'] . "\"" : '';

		$db = DB::connection('mongodb');
		$modlog = $db->collection('modlog');

		$query = $modlog->whereIn('mod', $input['mods'])
					->whereIn('action', $input['actions'])
					->where('created_at', '>', $input['startdate'])
					->where('created_at', '<', $input['enddate']);
		if ($input['author'] != '') {
			$query->where('target_author', $input['author']);
		}
		if ($input['detail'] != '') {
			$query->where('$text', ['$search' => $input['detail']]);
		}
		$results = $query->orderBy('created_at', ($input['order'] == 1 ? 'asc' : 'desc'))->take(1500)->paginate(50);

		$filters = [
			'start' => number_format($results->perPage() * ($results->currentPage() - 1) + 1),
			'end' => number_format($results->perPage() * $results->currentPage()),
			'total' => number_format($results->total()),
			'from' => strftime('%d %B %Y', $input['startdate']),
			'to' => strftime('%d %B %Y', $input['enddate']),
			'author' => $input['author'] != '' ? $input['author'] : "anyone",
			'detail' => $input['detail'] != '' ? $input['detail'] : "anything"
		];

		$data = [
			'labels' => $this->labels,
			'filters' => $filters,
			'results' => $results
		];

		return view('log.mod.queryresults', $data);
	}

	/**
	 * Show the results of the filtered modlog information
	 *
	 * @return Response
	 */
	public function filter(Request $request)
	{
		$input = $request->all();
		$_SESSION['input'] = $input;

		$input['mods'] = array_values($input['mods']);
		$input['actions'] = array_values($input['actions']);
		$input['startdate'] = strtotime($input['startdate']);
		$input['enddate'] = strtotime($input['enddate']) + 60 * 60 * 24;

		$filters = [
			'from' => strftime('%d %B %Y', $input['startdate']),
			'to' => strftime('%d %B %Y', $input['enddate'])
		];

		$db = DB::connection('mongodb');
		$modlog = $db->collection('modlog');

		/* Monstrous query to filter out the data from the form */
		$modHistory = $modlog->raw(function($collection) use ($input)
		{
			return $collection->aggregate([
				[
					'$match' => [
						'mod' => [
							'$in' => $input['mods']
						],
						'action' => [
							'$in' => $input['actions']
						],
						'created_at' => [
							'$gte' => $input['startdate'],
							'$lte' => $input['enddate']
						]
					],
				],
				[
					'$group' => [
						'_id' => '$mod',
						'count' => [
							'$push' => [
								'_id' => '$action'
							]
						],
						'total' => [
							'$sum' => 1
						]
					]
				],
				[
					'$sort' => [
						'total' => -1
					]
				]
			]);
		})['result'];

		/* Generate data to send to the table */
		$result = [];

		$totals = [];
		foreach ($input['actions'] as $i => $str) {
			$totals = array_merge($totals, [$str => 0]);
		}
		$totals['total'] = 0;
		$totals['percent'] = 0;

		$totalActions = 0;
		
		foreach ($modHistory as $mod) {
			if (!isset($result[$mod['_id']])) {
				$result[$mod['_id']] = [];
			}
			foreach ($mod['count'] as $key => $action) {
				if (!isset($result[$mod['_id']][$action['_id']])) {
					$result[$mod['_id']][$action['_id']] = 0;
				}
				$result[$mod['_id']][$action['_id']] += 1;
				$totalActions += 1;
			}
			$result[$mod['_id']]['total'] = $mod['total'];
		}
		foreach ($modHistory as $mod) {
			$result[$mod['_id']]['percent'] = $mod['total'] / $totalActions * 100;
			if ($mod == 'Jpon9') { dd($result[$mod['_id']]); }
			foreach ($result[$mod['_id']] as $action => $number) {
				$totals[$action] += $number;
			}
		}

		$labels = [];

		/* Grab labels for the actions */
		foreach ($input['actions'] as $action) {
			if (array_key_exists($action, $this->labels)) {
				$labels = array_merge($labels, [$action => $this->labels[$action]]);
			}
		}

		$data = [
			'mods' => $result,
			'labels' => $this->labels,
			'actionsList' => $labels,
			'filters' => $filters,
			'totals' => $totals
		];

		return view('log.mod.filterresults', $data);
	}
}