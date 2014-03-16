<?php
class RF_Form_Element_MonthYear extends Zend_Form_Element 
{
	
    public function init()
    {
        parent::init();
        $this->addDecorator('ViewScript', array(
            'viewScript' => 'common/monthYear.phtml'
        ));
    }
    
    public function setValue($value)
    {
       	if(is_array($value))
        {
            @list($month, $year) = $value;			
            if($month==0 && $year==0){
            	$value = 0;
            }elseif(ereg("end",$this->label)){
            	$value = mktime(0,0,0,$month+1,-1,$year);
            }elseif(eregi("start",$this->label)){
            	$value = mktime(0,0,0,$month,1,$year);
            }
        }
       	        
        return parent::setValue($value);
    }
}

