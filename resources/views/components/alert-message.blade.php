@if ($message = Session::get('error'))
<div class="alert alert-danger" role="alert">
    {{ $message }}
</div>
@endif

@if ($errors->any())
    <div {{ $attributes }}>
        <h3>{{ __('Whoops! Something went wrong.') }}</h3>
        <ul class="">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($message = Session::get('success'))
<div class="alert alert-success" role="alert">
    {{ $message }}
</div>
@endif
