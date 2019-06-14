<?php
$is_replies = $is_replies ?? false;
?>
@if(empty($comments) && !$is_replies)
<div class="alert alert-info">
	<p>No Comments Yet</p>
</div>
@endif

@foreach($comments as $comment)
<div class="card{{ ($is_replies) ? ' ml-5' : '' }}">
	<div class="card-header">
		<p class="my-0">
			<span class="text-muted">{{ $comment->id }}</span>
			<strong>{{ $comment->username }}</strong>, {{ $comment->created_at->diffForHumans()}}
		</p>
	</div>
	<div class="card-body">
		<div class="mb-2">{{ $comment->content }}</div>
		@if($comment->replies)
			@include('front.comments._list', [
				'comments' => $comment->replies,
				'is_replies' => true
			])
		@endif
	</div>
</div>
@endforeach
