@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Vänligen bekräfta aktivering</div>
                <div class="card-body">

                    <form method="post" action="{{action('OTPController@confirm')}}" accept-charset="UTF-8">
                        @csrf

                        <input type="hidden" name="username" value="{{$username}}">
                        <input type="hidden" name="serial" value="{{$serial}}">

                        Genom att klicka på "Bekräfta aktivering" nedan så intygar du att du har<br>
                        kontrollerat identiteten på den som ska ha dosan och att du har informerat<br>
                        om villkoren för användningen av den.<br><br>
                        Person: {{$name}}<br>
                        Serienummer: {{$serial}}<br><br>

                        @isset($existingToken)
                            OBS! Användaren har redan en OTP-dosa registrerad sedan innan.<br>
                            Eftersom en användare enbart kan ha en dosa registrerad på sig<br>
                            så kommer denna dosa att avregistreras ifrån användaren!<br>
                            Serienummer på denna dosa: {{$existingToken->tokenId}}<br><br>
                            <input type="hidden" name="existingTokenId" value="{{$existingToken->tokenId}}">
                        @endisset

                        @isset($existingUser)
                            OBS! Denna dosa är redan registrerad på en annan användare.<br>
                            Eftersom en dosa enbart kan vara registrerad på en användare<br>
                            så kommer dosan att avregistreras ifrån den användaren!<br><br>
                            <input type="hidden" name="existingUser" value="{{$existingUser}}">
                        @endisset

                        <a href="/" class="btn btn-secondary">Avbryt</a>
                        <button class="btn btn-primary" type="submit">Bekräfta aktivering</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
