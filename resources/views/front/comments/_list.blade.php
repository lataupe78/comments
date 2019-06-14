@forelse($comments as $comment)
<div class="card">
	<div class="card-header">
		<p>
			<span class="text-muted">{{ $comment->id }}</span>
			<strong>{{ $comment->username }}</strong>, {{ $comment->created_at->diffForHumans()}}
		</p>
	</div>
	<div class="card-body">
		{{ $comment->content }}
	</div>
</div>

@empty
<div class="alert alert-info">
	<p>No Comments Yet</p>
</div>
@endforelse
