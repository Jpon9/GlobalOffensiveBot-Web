<?php

/**
 * Custom file by Jpon9 to facilitate admins creating users without the authing redirect middleware
 */

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait AdminCreatesUsers
{
    /**
     * Creates a user from the admin panel
     * @param  Request $request
     * @return Route / redirect
     */
    public function adminRegisterUser(Request $request) {
        $loginDetails = [
            "username" => $username,
            "password" => $password,
            "password_confirmation" => $password_confirmation
        ];

        $validator = $this->validator($loginDetails);

        dd($validator);

        if ($validator->fails()) {
            $this->throwValidationException($loginDetails, $validator);
            return redirect($this->redirectPath());
        }


        return Redirect::route('admin/users', ["success_message" => "Success! The user has been created."]);
    }
}
