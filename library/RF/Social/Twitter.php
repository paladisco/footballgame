<?php
	class RF_Social_Twitter
	{
		private $_feed;
		private $_username;
		
		private function niceTime($time) {
		  $delta = time() - $time;
		  if ($delta < 60) {
		    return 'less than a minute ago.';
		  } else if ($delta < 120) {
		    return 'about a minute ago.';
		  } else if ($delta < (45 * 60)) {
		    return floor($delta / 60) . ' minutes ago.';
		  } else if ($delta < (90 * 60)) {
		    return 'about an hour ago.';
		  } else if ($delta < (24 * 60 * 60)) {
		    return 'about ' . floor($delta / 3600) . ' hours ago.';
		  } else if ($delta < (48 * 60 * 60)) {
		    return '1 day ago.';
		  } else {
		    return floor($delta / 86400) . ' days ago.';
		  }
		}
		
		
	 	public function __construct($username,$count=1)
	    {
	    	$this->_username = $username;
	    	$url = sprintf("http://twitter.com/statuses/user_timeline/%s.xml?count=%d", $username, $count);
	    	$curl_handle = curl_init();
	    	curl_setopt($curl_handle, CURLOPT_URL, $url);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Expect:'));
			$twitter_data = curl_exec($curl_handle);
	    	
	    	$this->_feed = new SimpleXMLElement($twitter_data);
	    }

	    public function getTweets(){
	    	foreach($this->_feed as $status) {
			    $message = preg_replace("/http:\/\/(.*?)\/[^ ]*/", '<a href="\\0" target="_blank">\\0</a>',
			        $status->text);
			    $time = $this->niceTime(strtotime(str_replace("+0000", "", $status->created_at)));
			    $tweets[] = array('message' => $message, 'time' => $time, 'profile_image_url' => $status->user->profile_image_url);
		  	}
		  	return $tweets;
	    }

	}
?>