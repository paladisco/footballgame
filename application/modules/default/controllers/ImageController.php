<?php

/**
 * Index controller
 *
 * Default controller for this application.
 * 
 * @uses       Zend_Controller_Action
 * @package    QuickStart
 * @subpackage Controller
 */
class ImageController extends Zend_Controller_Action
{
	protected $_processor;
	protected $_cache;
	
	public function init(){
		parent::init();
		
		$this->_processor = new RF_Image_Resizer();
		
		$this->_cache = Zend_Registry::get('imageCache');
			
	}
	
	public function indexAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
    	$this->_helper->layout->disableLayout();
        
    	$w = (int) $this->_getParam('w');
	    $h = (int) $this->_getParam('h');
	    $filename = $this->_getParam('filename');
		$path = $this->_getParam('path');
		$crop = $this->_getParam('crop');
		
		/* if the image 'contentID_languageID' does not exist, try image 'contentID'
		clearstatcache();
		if(!file_exists(DOCUMENT_ROOT.'/images/uploaded/'.$filename))
		{
			$split = preg_split('/_/', $filename);
			$filename = $split[0];
		}
		*/
		
		if($mod_since = $this->getRequest()->getHeader('If-Modified-Since')){
			
			$request_modified = explode(';', $mod_since);
    		$request_modified = strtotime($request_modified[0]);
    		
		}
	    if ($this->getFiletime($path,$filename)>0 && $this->getFiletime($path,$filename) <= $request_modified) {
	    	
	    	header('HTTP/1.1 304 Not Modified');
	      	exit();
	      	
	    }else{
	    
		    $mimetype = $this->getMIMEType($path,$filename);
		    $format = substr(strstr($mimetype,"/"),1);
		    //echo $mimetype."(".$format.")";
		    $this->getResponse()->setHeader('Content-type', $mimetype);
		    $expires = 60 * 60 * 24 * 3;
			$exp_gmt = gmdate("D, d M Y H:i:s", time() + $expires)." GMT";
			$mod_gmt = gmdate("D, d M Y H:i:s", $this->getFiletime($path,$filename))." GMT";

            $cache_id = str_replace(array(".","-"),array('','_'),$path."_".$filename."_".$w."_".$h."_".$crop."_".$format);
            if(!$result = $this->_cache->load($cache_id)){
                if($format=='gif'){
                    $imagedata = $this->_processor->getRawImage($path,$filename);
                }else{
                    $imagedata = $this->_processor->processImage($path,$filename, $w, $h, $crop, $format);
                }
                $result = $imagedata;
                $this->_cache->save($result,$cache_id,array('image',$path.'_'.str_replace(array(".","-"),array('','_'),$filename)));
            }
				
			$this->getResponse()->setHeader('Expires',$exp_gmt);
			$this->getResponse()->setHeader('Last-Modified',$mod_gmt);
			$this->getResponse()->setHeader('Cache-Control','public, max-age='.$expires);
			$this->getResponse()->setHeader('Pragma','!invalid');
			//$this->getResponse()->setHeader('Content-Length', strlen($imagedata));
			$this->getResponse()->setHeader('ETag',md5($result));
	

			echo $result;
	    }
    }
    
	public function hiresAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
    	$this->_helper->layout->disableLayout();
        
    	$filename = $this->_getParam('filename');
		$path = split("_",$this->_getParam('path'));
		
  		$mimetype = $this->getMIMEType($path[0]."_".$path[1],$filename);
	    $format = substr(strstr($mimetype,"/"),1);
	    
    	header('Content-type: '.$mimetype);
		header('Content-Disposition: attachment; filename="'.$filename.'.'.$format);
		readfile(DOCUMENT_ROOT.'/images/uploaded/'.$path[1].'/'.$filename);
    	
    }
    
	private function getMIMEType($path,$filename){
   		$imgsize = getimagesize(DOCUMENT_ROOT."/images/".str_replace("_","/",$path)."/".$filename);
   		return $imgsize['mime'];
   	}
   	
	private function getFiletime($path,$filename){
   		$time = filemtime(DOCUMENT_ROOT."/images/".str_replace("_","/",$path)."/".$filename);
   		return $time;
   	}
   
}

