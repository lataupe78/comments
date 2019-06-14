<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $guarded = [];

	public function comments(){
		return $this->morphMany(Comment::class, 'commentable');
	}
}
