<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OTPController extends Controller
{
    public function __construct()
    {
        $this->middleware('authnodb');
    }

    public function activate(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'serial' => 'required',
            'serial' => 'exists:otp,serial',
        ],
        [
            'username.required' => 'Du måste ange ett användarnamn!',
            'serial.required' => 'Du måste ange ett serienummer!',
            'serial.exists' => 'Du har angett ett ogiltigt serienummer!',
        ]);

        $user = session()->get('user');

        $data = [
            'user' => $user,
        ];

        return view('otp.success')->with($data);
    }

}
