<?php
class ConfigController extends Local_Controller_Backend
{
	private $_model;
	private $_form;
	
	public function init(){
		parent::init();
		$this->_model = new Local_Model_DbTable_Config();
		$this->_form = new Default_Form_Config();
	}
	
	public function indexAction()
    {
		$this->view->title = "Persönliche Konfiguration";
		
    	if ($this->getRequest()->isPost()) { 
            $formData = $this->getRequest()->getPost(); 
            if ($this->_form->isValid($formData)) { 
                $f = $this->_form->getValues(); 
                
                $this->_model->updateConfig($this->_identity->getID(),$f);
                
//                $this->_redirect($this->view->url(array('action'=>'index')));
                
            } else { 
                 
            	$this->_form->populate($formData);
            } 
        }else{

        	$f = array();
        	$config = $this->_model->getByUser($this->_identity->getID());
        	foreach($config as $c){
        		$f[$c->constant] = ($c->value!=''?$c->value:$c->default);
        	}
            $this->_form->populate($f); 
            
		}

		$this->view->form = $this->_form;
    }
    
   
    public function editPasswordAction()
    {
		$this->view->title = "Passwort ändern";
		
		$userModel = new Local_Model_DbTable_User();
		$currentPassword = $userModel->fetchRow('id='.(int)$this->_identity->getID())->password;	
		
    	$passForm = new Default_Form_Password();
        if ($this->getRequest()->isPost()) { 
            $formData = $this->getRequest()->getPost(); 
            if ($passForm->isValid($formData) && md5($formData['current_password'])==$currentPassword && $formData['new_password']==$formData['new_password_confirm']){ 
            	$f = $passForm->getValues(); 
                $userModel->updatePassword($f['new_password'],$this->_identity->getID());
                $this->view->message = "Passwort erfolgreich geändert!";
            } else { 
                $passForm->populate($formData);
                if(md5($formData['current_password'])!=$currentPassword){
                	$passForm->current_password->addError('Aktuelles Passwort wurde falsch eingegeben!');
                }
                if($formData['new_password']!=$formData['new_password_confirm']){
                	$passForm->new_password_confirm->addError('Passwörter stimmen nicht überein!');
                }
            } 
        }
    	$this->view->form = $passForm;
    }
    

}





















