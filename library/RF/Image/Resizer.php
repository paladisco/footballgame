<?php
require_once("class.upload.php");

class RF_Image_Resizer{

	public function processImage($path,$filename,$x,$y,$crop='crop',$format='jpg'){
		$filename = DOCUMENT_ROOT."/images/".str_replace("_","/",$path)."/".$filename;
		if(file_exists($filename)){
			$handle = new upload($filename);
			$handle->image_resize         = true;
		    $handle->image_x              = $x;
		    $handle->image_y              = $y;
		    $handle->jpeg_quality 		  = 90;
		    $handle->image_convert		  = $format;
		    if($crop=='crop'){
		    	$handle->image_ratio_crop = true;
		    }elseif($crop=='ratio'){
		    	$handle->image_ratio	  = true;
		    }elseif($crop=='fill'){
		    	$handle->image_ratio_fill = true;
		    }
	        if($processed = $handle->process()){
		    	return $processed;
		    }else{
		    	throw new Zend_Exception ("Error processing Image: ".$handle->error."(trying to process ".$filename.")!");
		    }
		}else{
            // If image doesn't exist, render a placeholder image
            if($x==0){
                $x=$y;
            }
            $url = 'http://placehold.it/'.$x.'x'.$y;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $head = curl_exec($ch);
            curl_close($ch);
            return $head;
        }
	}
}
?>