<?php
class RF_View_Helper_FormatURL extends Zend_View_Helper_Abstract
{
    public function formatURL($href)
    {
    	if(substr($href,0,1)=="/"){
    		$href=HTTP_ROOT.$href;
    	}
    	elseif(substr($href,0,7)!="http://"){
			$href="http://".$href;
		}
		return $href;
    }
}