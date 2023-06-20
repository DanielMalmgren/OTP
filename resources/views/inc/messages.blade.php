@if($errors->any())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {!!$error!!}
            <a href="#" class="close" data-dismiss="alert" aria-label="close" onclick="$(this).parent().remove();">&times;</a>
        </div>
    @endforeach
@endif

@if(session('success'))
    <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" onclick="$(this).parent().remove();">&times;</a>
        {!!session('success')!!}
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" onclick="$(this).parent().remove();">&times;</a>
        {!!session('warning')!!}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" onclick="$(this).parent().remove();">&times;</a>
        {!!session('error')!!}
    </div>
@endif

@php
    \Session::forget(['warning', 'success', 'error']);
@endphp


@if(!empty(Session('notification_collection')))
    @foreach (Session('notification_collection') as $notification)
        <div class="alert alert-{{ $notification['notification_type'] }}" role="alert">
            {{ $notification['notification_message'] }}
            <a href="#" class="close" data-dismiss="alert" aria-label="close" onclick="$(this).parent().remove();">&times;</a>
        </div>
    @endforeach
@endif
