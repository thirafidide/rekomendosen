<?php

class UpvoteDownvote extends Model {

	const UPVOTE = 1;
	const DOWNVOTE = 0;
	
	protected $table 		= 'upvotedownvote';
	protected $primaryKey 	= 'id';
	
	public function reviews() {
		return $this->belongsTo('Review');
	}
	
	public function penggunas() {
		return $this->belongsTo('Pengguna');
	}

}