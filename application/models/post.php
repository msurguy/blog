<?php

// The model for blog posts. 
// the post belongs to a user and that relationship is identified by post_author in the Posts table

class Post extends Eloquent {

	public function user()
	{
		return $this->belongs_to('User','post_author');
	}

}
