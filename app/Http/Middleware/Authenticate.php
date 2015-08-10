<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth;
use Redirect;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return view('errors.401');
            } else {
                return redirect()->guest('login');
            }
        }

        $permission = $this->userHasPermission($request->path());

        if (gettype($permission) == 'boolean' && !$permission) {
            return view('errors.401');
        } else if (gettype($permission) != 'boolean') {
            return $permission;
        }

        return $next($request);
    }

    /**
     * Checks if the user has the appropriate permissions
     * @param  string $requiredPerms A space-separated list of necessary permissions
     * @return mixed                 false if user has insufficient permissions,
     *                               true if the user has sufficient permissions,
     *                               view to a no-permissions page if they have no permissions
     */
    protected function userHasPermission($page) {
        $user = Auth::user();

        if ($user->permissions == '') {
            return redirect('no-permissions');
        }

        // * or % as the user's permission means full access
        if (gettype(strpos($user->permissions, '*')) != 'boolean') {
            return true;
        }
        if (gettype(strpos($user->permissions, '%')) != 'boolean') {
            return true;
        }

        // These pages are open to any authenticated user
        if (gettype(array_search($page, config('perms.open_pages'))) != 'boolean') {
            return true;
        }

        $userPerms = explode(' ', $user->permissions);

        $pagesUserCanAccess = [];

        foreach ($userPerms as $role) {
            if (config('perms.roles.' . $role) != null) {
                $pagesUserCanAccess = array_merge($pagesUserCanAccess, config('perms.roles.' . $role));
            }
        }

        return gettype(array_search($page, $pagesUserCanAccess)) != 'boolean';
    }
}
