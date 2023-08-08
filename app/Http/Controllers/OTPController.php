<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OTP;

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
            'existingToken' => XPIController::GetUserOtp($request->username),
            'existingUser' => OTP::where('serial', $request->serial)->first()->user,
        ];

        return view('otp.confirm')->with($data);
    }

    public function confirm(Request $request)
    {
        if(isset($request->existingTokenId)) {
            XPIController::DeactivateOtp($request->username, $request->existingTokenId);
            $otp = OTP::where('serial', $request->existingTokenId)->first();
            $otp->status = null;
            $otp->user = null;
            $otp->save();
        }

        if(isset($request->existingUser)) {
            XPIController::DeactivateOtp($request->existingUser, $request->serial);
        }

        try {
            XPIController::ActivateOtp($request->username, $request->serial);
        } catch(\Exception $e) {
            logger($user.name.' caught exception: '.$e->getMessage());
            $data = [
                'error' => $e->getMessage(),
            ];

            return view('otp.failure')->with($data);
        }

        $otp = OTP::where('serial', $request->serial)->first();
        $otp->status = 'assigned';
        $otp->user = $request->username;
        $otp->save();

        $data = [
            'pin' => $otp->pin,
        ];

        return view('otp.success')->with($data);
    }

    public function check(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
        ],
        [
            'username.required' => 'Du måste ange ett användarnamn!',
        ]);

        $user = session()->get('user');

        if(!$user->isAdmin) {
            abort(403);
        }

        $aduser = \LdapRecord\Models\ActiveDirectory\User::where('sAMAccountName', $request->username)->first();

        if(is_null($aduser)) {
            $data = [
                'error' => "Felaktigt användarnamn",
            ];

            return view('otp.failure')->with($data);
        }

        $token = XPIController::GetUserOtp($request->username);
        if($token) {
            $otp = OTP::where('serial', $token->tokenId)->first();
        } else {
            $otp = null;
        }

        $data = [
            'name' => $aduser->displayname[0],
            'otp' => $otp,
        ];

        return view('otp.check')->with($data);
    }
}
