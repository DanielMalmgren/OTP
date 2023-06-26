<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RicorocksDigitalAgency\Soap\Facades\Soap;

class OtpActivate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:otp-activate {username} {serial}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate OTP device and assign it to an user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        logger("Adding OTP device ".$this->argument('serial')." to user ".$this->argument('username').".");

        $usernamenode = ['key'=>'username','value'=>env("XPI_USERNAME")];
        $passwordnode = ['key'=>'password','value'=>env("XPI_PASSWORD")];
        $subject = ['credentials' => [$usernamenode, $passwordnode]];

        $subject = Soap::to(env("XPI_BASEURL").'Authentication?wsdl')->
                call('authenticate', ['subject' => $subject, 'method' => env("XPI_AUTHMETHOD")])->response->return;

        $oathproviders = Soap::to(env("XPI_BASEURL").'OATH?wsdl')->
                call('getProviders', ['subject' => $subject])->response->return;

        foreach($oathproviders as $curprovider) {
            if($curprovider->name == env("XPI_OATHPROVIDERNAME")) {
                $provider = $curprovider;
                break;
            }
        }

        $oathproperties = Soap::to(env("XPI_BASEURL").'OATH?wsdl')->
                call('getOATHProperties', ['subject' => $subject, 'username' => $this->argument('username')])->response->return;

        $oathtoken = ['enabled'=>1,'provider'=>$provider,'revocationStatus'=>0,'tokenId'=>$this->argument('serial')];

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
}
