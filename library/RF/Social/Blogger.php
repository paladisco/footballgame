<?php
class RF_Social_Blogger {

	private $_blog_id;
	private $_feed;
	
 	public function __construct($blog_id,$count=3) {
		$this->_blog_id = $blog_id;
		$this->_feed = simplexml_load_file('http://www.blogger.com/feeds/'.$this->_blog_id.'/posts/default?max-results='.$count);
	}

	public function getFeed(){
		return $this->_feed;
	}
	
	public function getLatestEntry(){
		return $this->_feed->entry[0];
	}
	
}