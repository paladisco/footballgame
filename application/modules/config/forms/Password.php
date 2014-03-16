<?php
class Config_Form_Password extends RF_Form_BootstrapForm
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options); 
        $this->setName('login'); 

		$password = new Zend_Form_Element_Password('current_password');
	    $password->setLabel('Bisheriges Passwort')
	            ->setRequired(true);
	
	    $password_new = new Zend_Form_Element_Password('new_password');
	    $password_new->setLabel('Neues Passwort')
	            ->setRequired(true);
	
	    $password_new_conf = new Zend_Form_Element_Password('new_password_confirm');
	    $password_new_conf->setLabel('Neues Passwort bestätigen')
	            ->setRequired(true);
	
	    $submit = new Zend_Form_Element_Submit('submit');
	    $submit->setLabel('Passwort ändern');
	
	    $this->addElements(array($password,$password_new,$password_new_conf,$submit));
    }
}