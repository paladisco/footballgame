<?php
class Login_Form_Login extends RF_Form_StandardDecoratedForm
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options); 
        $this->setName('login'); 

		$username = new Zend_Form_Element_Text('username');
	    $username->setLabel('Username:')
	            ->setRequired(true);
	
	    $password = new Zend_Form_Element_Password('password');
	    $password->setLabel('Password:')
	            ->setRequired(true);
	
	    $referrer_url = new Zend_Form_Element_Hidden('referrer_url');
	    $referrer_url->setDecorators($this->_hiddenDecorators);
	    
	    $submit = new Zend_Form_Element_Submit('submit');
	    $submit->setLabel('Login')
            ->setAttrib('class','btn');
	
	    $this->addElements(array($username,$password,$referrer_url,$submit));
	    $this->loadDefaultDecorators(array('id','submit','referrer_url'));
    }
}