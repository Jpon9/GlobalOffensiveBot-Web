<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Loggable;
use Illuminate\Http\Request;
use App;
use Input;
use Redirect;

class AdminUsersController extends Loggable {

	/**
	 * Show the Users page
	 */
	public function index($success = false, $forceRedirect = false)
	{
		$data = ['users' => App\User::all()];

		if ($success) {
			$data['success_message'] = $success;
			if ($forceRedirect) {
				return Redirect::route('admin/users', $data);
			}
		}

		return view('admin.users', $data);
	}

	/**
	 * Load user profile for editing
	 */
	public function loadUser($id, $success = false) {
		$user = App\User::find($id);
		if ($user == null) {
			return view('admin.user-not-found');
		}

		$data['user'] = $user;

		if ($success) {
			$data['success_message'] = 'Success! The user has been updated.';
		}

		return view('admin.user', $data);
	}

	/**
	 * Updates user profile after editing
	 */
	public function updateUser($id) {
		$input = Input::all();

		if (isset($input['delete'])) {
			App\User::destroy($id);
			return $this->index("Success! The user has been deleted.", true);
		}

		$user = App\User::find($id);
		$user->username = $input['username'];
		$user->permissions = $input['permissions'];
		$user->save();
		return $this->loadUser($id, "Success! The user has been updated.");
	}

	/**
	 * Register User
	 */
	public function registerUser($username, $password) {
		
	}
}