@if (Session::get('success')!==null)
    <div class="alert alert-success" role="alert">
        {{ Session::get('success') }}
    </div>
@endif
@if (Session::get('error')!==null)
    <div class="alert alert-danger" role="alert">
        ERROR!!!: {{ Session::get('success') }}
    </div>
@endif