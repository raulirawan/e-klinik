@if (Session::has('success'))
    <div class="alert alert-secondary rounded-pill alert-dismissible fade show">
        {{ Session::get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
    </div>
@endif


@if (Session::has('error'))
    <div class="alert alert-danger rounded-pill alert-dismissible fade show">
        {{ Session::get('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                class="bi bi-x"></i></button>
    </div>
@endif

@if (Session::has('errors'))
    <div class="alert alert-danger rounded-pill alert-dismissible fade show">
        @if ($errors->count() > 1)
            @foreach ($errors->all() as $error)
                <span>{{ $error }}</span>
            @endforeach
        @else
            <span>{{ $errors->first() }}</span>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                class="bi bi-x"></i></button>
    </div>
@endif
