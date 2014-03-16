<?php
	class RF_Social_YouTube extends Zend_View_Helper_Abstract
	{
		private $_feed;
			    
	    public function __construct($username)
	    {
	    	$yt = new Zend_Gdata_YouTube();
			$this->_feed = $yt->getuserUploads($username);
	    }
	    
	    public function getLatestVideoID(){
	    	return $this->_feed[0]->getVideoId();
	    }
	    
	    public function getThumbnail($video_id){
	    	return "http://img.youtube.com/vi/".$video_id."/default.jpg";
	    } 
	    
	    public function renderList(){
	    	$code = "";
	    	foreach($this->_feed as $entry){
	    		$videoThumbnails = $entry->getVideoThumbnails();
				$videothumbnail = $videoThumbnails[0]['url'];
				$timestamp = strtotime($entry->getPublished());
				$code .= '<div class="element">
					<a href="'.$entry->getVideoWatchPageUrl().'" onclick="loadVideo(\''.$entry->getVideoId().'\');return false;" target="_blank">
					<img src="'.$videothumbnail.'" class="thb">
					</a>
					<div class="title"><a href="'.$entry->getVideoWatchPageUrl().'" onclick="loadVideo(\''.$entry->getVideoId().'\');return false;" target="_blank">'.$entry->getTitle().'</a></div>
					<div class="info">'.$entry->getVideoViewCount().' Views<br />
					Das Blaue Wunder 2010</div>
					<div class="clearer"></div>
				</div>';
	    	}
	    	
	    	return $code;
	    }
	    
	    public static function renderVideo($video_id,$width=425,$height=344,$border=0,$fs=1,$color1='',$color2=''){

			$url = 'http://www.youtube.com/v/'.$video_id.'?rel=0&border='.$border.'&fs='.$fs;
			if($color1!=''){
				$url.= '&color1=0x'.$color1;
			}
			if($color2!=''){
				$url.= '&color2=0x'.$color2;
			}
			
			$embed_code .= '<script type="text/javascript" src="/js/swfobject.js"></script>    
			  <div id="ytapiplayer">
			    You need Flash player 8+ and JavaScript enabled to view this video.
			  </div>
			
			  <script type="text/javascript">
			
			    var params = { allowScriptAccess: "always", wmode: "transparent" };
			    var atts = { id: "myytplayer" };
			    swfobject.embedSWF("'.$url.'", "ytapiplayer", "'.$width.'", "'.$height.'", "8", null, null, params, atts);
			
			  </script>';
			return $embed_code;
	    }
	    
		public static function getIdFromUrl($ytURL) {
		
			$ytvIDlen = 11;	// This is the length of YouTube's video IDs
			
			// The ID string starts after "v=", which is usually right after 
			// "youtube.com/watch?" in the URL
			$idStarts = strpos($ytURL, "?v=");
			
			// In case the "v=" is NOT right after the "?" (not likely, but I like to keep my 
			// bases covered), it will be after an "&":
			if($idStarts === FALSE)
				$idStarts = strpos($ytURL, "&v=");
			// If still FALSE, URL doesn't have a vid ID
			if($idStarts === FALSE)
				die("YouTube video ID not found. Please double-check your URL.");
			
			// Offset the start location to match the beginning of the ID string
			$idStarts +=3;
			
			// Get the ID string and return it
			$ytvID = substr($ytURL, $idStarts, $ytvIDlen);
			
			return $ytvID;
		}
	}
?>