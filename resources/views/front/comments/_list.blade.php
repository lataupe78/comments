<?php
$is_reply = $is_reply ?? false;
?>
@if(empty($comments) && !$is_reply)
<div class="alert alert-info">
	<p>No Comments Yet</p>
</div>
@endif

@foreach($comments as $comment)

@if(($comment->reply_to === null && !$is_reply) || $is_reply)
<div class="card{{ ($is_reply) ? ' ml-5' : '' }}">
	<div class="card-header">
		<p class="my-0">
			<span class="text-muted">{{ $comment->id }}</span>
			<strong>{{ $comment->username }}</strong>, {{ $comment->created_at->diffForHumans()}}
		</p>
	</div>

	<div class="card-body">
		{{-- dump($comment->reply_to) --}}
		<div class="mb-2">{{ $comment->content }}</div>

		@if($comment->replies)
			@include('front.comments._list', [
				'comments' => $comment->replies,
				'is_reply' => true
			])
		@endif

	</div>
</div>
@endif

@endforeach
