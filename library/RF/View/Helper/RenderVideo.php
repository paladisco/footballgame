<?php
class RF_View_Helper_RenderVideo extends Zend_View_Helper_Abstract
{
	private $_url;
	private $_width;
	private $_height;
	
    public function renderVideo($url,$width,$height)
    {
    	$this->_url = $url;
    	$this->_width = $width;
    	$this->_height = $height;
    	
    	if(strstr($url,'vimeo.com')){
    		return $this->renderVimeo();
    	}elseif(strstr($url,'youtube.com')){
    		return $this->renderYouTube();
    	}elseif(strstr($url,'simplex.tv')){
    		return $this->renderSimplex();
    	}else{
    		return "wrong URL Format";
    	}
    }
    
	private function renderVimeo(){
    	$vimeo = new RF_Social_Vimeo();
		return $vimeo->renderVideo($this->_url, $this->_width, $this->_height);	
    }
    
    private function renderSimplex(){
    	$embed_code .= '<script type="text/javascript" src="/js/swfobject.js"></script>    
		  <div id="simplexplayer">
		    You need Flash player 8+ and JavaScript enabled to view this video.
		  </div>
		
		  <script type="text/javascript">
		
		    var params = { allowScriptAccess: "always" };
		    var atts = { id: "myplayer" };
		    swfobject.embedSWF("'.$this->_url.'", "simplexplayer", "'.$this->_width.'", "'.$this->_height.'", "8", null, null, params, atts);
		
		  </script>';
		return $embed_code;
    }
    
	private function renderYouTube(){
    	$id = RF_Social_YouTube::getIdFromUrl($this->_url);
		return RF_Social_YouTube::renderVideo($id, $this->_width, $this->_height);
    }
}