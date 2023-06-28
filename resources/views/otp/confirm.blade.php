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

                        <a href="/" class="btn btn-secondary">Avbryt</a>
                        <button class="btn btn-primary" type="submit">Bekräfta aktivering</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
