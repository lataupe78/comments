<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $guarded = [];

	protected $casts = [
		'reply_to' => 'integer'
	];

	protected $with = 'replies';


	protected $hidden = ['email', 'ip'];

	protected $appends = ['email_md5', 'ip_md5'];


	public function scopeAllFor($query, $id, $type){
		return $query->where([
            'commentable_id' => $id,
            'commentable_type' => $type,
            'reply_to' => null,
        ])
        ->with([
        	'replies' => function($q){
        	return $q->orderBy('created_at', 'desc');
        	}
        ])
        ->orderBy('created_at', 'desc');
	}

	public function replies() {
		return $this->hasMany(Comment::class, 'reply_to');
	}

	public function getEmailMd5Attribute(){
		return md5($this->email);
	}
	public function getIpMd5Attribute(){
		return md5($this->ip);
	}

	public function commentable()
    {
        return $this->morphTo();
    }
}
