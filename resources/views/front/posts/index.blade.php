@extends('layouts.app')

@section('content')
<div class="container">

	<div class="card">
		<div class="card-header">
			Liste des Posts
		</div>
		<div class="card-body">

			<div class="list group">
				@foreach($posts as $post)
				<div class="list-group-item">
					<small class="text-muted">{{ $post->created_at->diffForHumans()}}</small>
					<h2>
						<a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
					</h2>
				</div>

				@endforeach
			</div>
		</div>
	</div>
</div>

@endsection
