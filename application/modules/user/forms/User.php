<?php 
class User_Form_User extends RF_Form_BootstrapForm
{ 
    public function __construct($options = null)
    { 
        parent::__construct($options); 
        $this->setName('user'); 
        
        $elements[] = new Zend_Form_Element_Hidden('id');

        $e = new RF_Form_Element_ImageUpload('image');
        $e->setLabel('Image Upload')
            ->addValidator('Size', false, array('max' => '5242880'))
            ->addValidator('Extension', false, 'jpg,jpeg,png,gif')
            ->setDestination('user/',Zend_Session::getId());
        $elements[] = $e;

        $elements[] = new Zend_Form_Element_Text('realname');
        end($elements)->setLabel('Vollständiger Name')
               ->setRequired(true) 
               ->addFilter('StringTrim'); 

        $elements[] = new Zend_Form_Element_Text('username');
        end($elements)->setLabel('Username') 
               ->setRequired(true) 
               ->addFilter('StringTrim');

        $elements[] = new Zend_Form_Element_Text('password');
        end($elements)->setLabel('Passwort')
            ->setRequired(true)
            ->addFilter('StringTrim');

        $elements[] = new Zend_Form_Element_Text('email');
        end($elements)->setLabel('E-Mail')
            ->setRequired(true)
            ->addFilter('StringTrim');

        $role = new Local_Model_DbTable_UserRole();
        $elements[] = new RF_Form_Element_CustomSelector('role_id');
        end($elements) ->setLabel('Berechtigungslevel')
            ->setOptions($role,'id');

        $elements[] = new Zend_Form_Element_Submit('submit');
        end($elements)->setLabel('erstellen'); 
        	
        $this->addElements($elements);
        $this->setAttrib('horizontal',true);
    } 
} 
