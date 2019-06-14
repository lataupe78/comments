@if (session('status'))
<div class="alert alert-success mb-4" role="alert">
	{{ session('status') }}
</div>
@endif

@if (session('errors'))
<div class="alert alert-danger mb-4" role="alert">
	{{ session('errors') }}
</div>
@endif


@if (session('success'))
<div class="alert alert-success mb-4" role="alert">
	{{ session('success') }}
</div>
@endif
