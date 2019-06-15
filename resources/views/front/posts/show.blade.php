@extends('layouts.app')

@section('content')
<div class="container py-5">

	<div class="card">
		<div class="card-body">
			<h1>{{ $post->title }}</h1>
		</div>
		<div class="card-footer">
			<p>
				<a href="{{ route('posts.index') }}">Retour</a>
			</p>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<p>
				<small class="text-muted">{{ $post->created_at->diffForHumans()}}</small>
			</p>
			{!! nl2br($post->content) !!}
		</div>
	</div>

	{{--
	<div class="card my-5 text-white bg-primary">
		@include('front.comments._list', ['comments' => $post->comments])
	</div>
	--}}

	<comments
		model="{{ get_class($post) }}"
		:id="{{ $post->id }}"
		ip="{{ md5(request()->ip()) }}" />

		{{-- :comments="{{$post->comments }}" --}}

</div>

@endsection

@section('content_bottom')
	{{-- dump($post->comments) --}}
@endsection
