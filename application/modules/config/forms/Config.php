<?php
class Config_Form_Config extends RF_Form_BootstrapForm
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options); 
        $this->setName('login'); 

        $model = new Local_Model_DbTable_Config();
        $config = $model->fetchAll();
        foreach($config as $c){
        	$element = new Zend_Form_Element_Text($c->constant);
	    	$element->setLabel($c->name)
	    	->setRequired(true);
			$e[] = $element;
        }
		    
	    $submit = new Zend_Form_Element_Submit('submit');
	    $submit->setLabel('speichern');
		$e[] = $submit;
	    
	    $this->addElements($e);
	    $this->loadDefaultDecorators(array('submit'));
    }
}