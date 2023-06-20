<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;

class LoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $samluser = $event->getSaml2User();
        $userattr = $samluser->getAttributes();

        //Behövs senare för SLO
        //session(['sessionIndex' => $samluser->getSessionIndex()]);
        //session(['nameId' => $samluser->getNameId()]);

        $user = new User($userattr["sAMAccountName"][0]);
        session()->put('user', $user);
    }
}
