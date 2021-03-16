<?php

namespace WcStudio\AdminUi\Http\Controllers\Auth;

use WcStudio\AdminUi\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as performLogout;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('adminui_auth::login');
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('adminui');
    }

    public function logout(Request $request)
    {
        $this->performLogout($request);
        return redirect()->route('adminui');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
        $this->middleware('guest')->except('logout');
    }
}
