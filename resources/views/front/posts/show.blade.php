@extends('layouts.app')

@section('content')
<div class="container">

	<div class="card">

		<div class="card-header">
			<h1>{{ $post->title }}</h1>
		</div>

		<div class="card-body">
			<p>
				<small class="text-muted">{{ $post->created_at->diffForHumans()}}</small>
			</p>
			{{ $post->content }}
		</div>

		<div class="card-footer">
			@include('front.comments._list', ['comments' => $post->comments])
		</div>
	</div>

</div>

@endsection
