<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RicorocksDigitalAgency\Soap\Facades\Soap;

class XPIController extends Controller
{
    static private function auth() {
        $usernamenode = ['key'=>'username','value'=>env("XPI_USERNAME")];
        $passwordnode = ['key'=>'password','value'=>env("XPI_PASSWORD")];
        $subject = ['credentials' => [$usernamenode, $passwordnode]];

        return Soap::to(env("XPI_BASEURL").'Authentication?wsdl')->
                call('authenticate', ['subject' => $subject, 'method' => env("XPI_AUTHMETHOD")])->response->return;
    }

    static public function ActivateOtp(String $username, String $serial) {
        $user = session()->get('user');

        if($user) {
            $opuser = $user->username;
        } else {
            $opuser = 'Local';
        }

        logger($opuser." is adding OTP device ".$serial." to user ".$username.".");

        $subject = self::auth();

        $oathproviders = Soap::to(env("XPI_BASEURL").'OATH?wsdl')->
                call('getProviders', ['subject' => $subject])->response->return;

        foreach($oathproviders as $curprovider) {
            if($curprovider->name == env("XPI_OATHPROVIDERNAME")) {
                $provider = $curprovider;
                break;
            }
        }

        $oathproperties = Soap::to(env("XPI_BASEURL").'OATH?wsdl')->
                call('getOATHProperties', ['subject' => $subject, 'username' => $username])->response->return;

        $oathtoken = ['enabled'=>1,'provider'=>$provider,'revocationStatus'=>0,'tokenId'=>$serial];

        $oathproperties->enabled = 1;
        $oathproperties->useDirectoryPwd = 1;
        //For some reason if there is only one token returned it doesn't come as an array but rather
        //one single object. Workaround for that follows:
        if(isset($oathproperties->oathTokens) && is_object($oathproperties->oathTokens)) {
            $temptoken = $oathproperties->oathTokens;
            $oathproperties->oathTokens = [];
            $oathproperties->oathTokens[] = $temptoken;
            $oathproperties->oathTokens[] = $oathtoken;
        } else {
            $oathproperties->oathTokens[] = $oathtoken;
        }

        Soap::to(env("XPI_BASEURL").'OATH?wsdl')->
                call('updateOATHProperties', ['subject' => $subject, 'oathProperties' => $oathproperties]);
    }

    static public function GetUserOtp(String $username) {
        $subject = self::auth();

        $oathproperties = Soap::to(env("XPI_BASEURL").'OATH?wsdl')->
                call('getOATHProperties', ['subject' => $subject, 'username' => $username])->response->return;

        if(isset($oathproperties->oathTokens) && is_object($oathproperties->oathTokens)) {
            if($oathproperties->oathTokens->provider->name == env("XPI_OATHPROVIDERNAME")) {
                return $oathproperties->oathTokens;
            }
        } elseif (is_array($oathproperties->oathTokens)) {
            foreach($oathproperties->oathTokens as $token) {
                if($token->provider->name == env("XPI_OATHPROVIDERNAME")) {
                    return $token;
                }
            }
        }
    }

    static public function DeactivateOtp(String $username, String $serial) {
        $user = session()->get('user');

        if($user) {
            $opuser = $user->username;
        } else {
            $opuser = 'Local';
        }

        logger($opuser." is removing OTP device ".$serial." from user ".$username.".");

        $subject = self::auth();

        $oathproperties = Soap::to(env("XPI_BASEURL").'OATH?wsdl')->
                call('getOATHProperties', ['subject' => $subject, 'username' => $username])->response->return;

        if(isset($oathproperties->oathTokens) && is_object($oathproperties->oathTokens)) {
            $oathproperties->oathTokens = null;
        } elseif (is_array($oathproperties->oathTokens)) {
            foreach($oathproperties->oathTokens as $key => $token) {
                if($token->tokenId == $serial) {
                    unset($oathproperties->oathTokens[$key]);
                    break;
                }
            }
            //Renumber the array keys, if they doesn't start at zero the SOAP call doesn't work
            $oathproperties->oathTokens = array_combine(range(0, count($oathproperties->oathTokens)-1), array_values($oathproperties->oathTokens));
        }

        Soap::to(env("XPI_BASEURL").'OATH?wsdl')->
                call('updateOATHProperties', ['subject' => $subject, 'oathProperties' => $oathproperties]);
    }
}
