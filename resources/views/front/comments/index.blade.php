@extends('layouts.app')

@section('content')

@foreach($comments as $comment)
<div class="card">
	<div class="card-header">
		<p>
			<strong>{{ $comment->username }}</strong>,
			{{ $comment->created_at->diffForHumans()}}
		</p>
	</div>
	<div class="card-body">
		{{ $comment->content }}
	</div>
</div>
@endforeach

@endsection
