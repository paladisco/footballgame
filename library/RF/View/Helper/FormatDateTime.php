<?php
class RF_View_Helper_FormatDateTime extends Zend_View_Helper_Abstract
{
	private $_timestamp;
	
	public function formatDateTime($timestamp=null)
    {
    	if(!$timestamp){
    		$timestamp = time();
    	}
    	if(!is_numeric($timestamp)){
    		$timestamp = strtotime($timestamp);
    	}
		$this->_timestamp = $timestamp;
		return $this;
    }
    
	public function fullString($time=false){
    	$format = "%A, %d. %B %Y";
    	if($time){
			$format.=" %R";
		}
    	return strftime($format, $this->_timestamp);
    }
    
	public function numericString($time=false){
    	setlocale(LC_TIME, "de_DE");
		$format = "%d.%m.%Y";
    	if($time){
			$format.=" %R";
		}
    	return strftime($format, $this->_timestamp);
    }
    
    public function hourMinString(){
    	$format = "%H:%M";
    	return strftime($format, $this->_timestamp);
    }
    
    public function minutesToTime($minutes){
    	$h = floor($minutes/60);
    	$m = str_pad($minutes%60,2,'0',STR_PAD_LEFT);
    	return $h.' h '.$m.' min';
    }
    
    public function monthName(){
    	$format = "%B %Y";
    	return strftime($format, $this->_timestamp);
    }
}