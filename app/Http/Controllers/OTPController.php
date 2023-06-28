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

        $aduser = \LdapRecord\Models\ActiveDirectory\User::where('sAMAccountName', $request->username)->first();

        if(is_null($aduser)) {
            $data = [
                'error' => "Felaktigt användarnamn",
            ];

            return view('otp.failure')->with($data);
        }

        $data = [
            'name' => $aduser->displayname[0],
            'username' => $request->username,
            'serial' => $request->serial,
        ];

        return view('otp.confirm')->with($data);
    }

    public function confirm(Request $request)
    {
        try {
            XPIController::ActivateOtp($request->username, $request->serial);
        } catch(\Exception $e) {
            logger($user.name.' caught exception: '.$e->getMessage());
            $data = [
                'error' => $e->getMessage(),
            ];

            return view('otp.failure')->with($data);
        }

        $data = [
            'username' => $request->username,
            'serial' => $request->serial,
        ];

        return view('otp.success')->with($data);
    }
}
