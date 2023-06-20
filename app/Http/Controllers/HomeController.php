<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('authnodb');
    }

    private function getUser(Request $request)
    {
        $user = session()->get('user');
        if($user->isAdmin && $request->username !== null) {
            return new User($request->username);
        } else {
            return session()->get('user');
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = session()->get('user');
        $asuser = $this->getUser($request);

        $data = [
            'user' => $user,
            'asuser' => $asuser,
        ];

        return view('home')->with($data);
    }

    public function logout()
    {
        session()->flush();
        return view('logout');
    }

}
