<?php
class Local_Controller_Backend extends Local_Controller_Action
{
    public function init(){

        parent::init();

        if($this->_auth){
            if (!$this->_auth->hasIdentity() && ($this->getRequest()->getModuleName()!="login")){
                $this->_redirect($this->view->url(array('module'=>'login'),'default',true));
            }elseif(!$this->_auth->hasIdentity()){
                $this->_initLoginForm();
            }elseif($this->_auth->hasIdentity() && !$this->_identity->getActive()){
                $this->_auth->clearIdentity();
                $this->_redirect($this->view->url(array('module'=>'login'),'default',true));
            }elseif($this->_allowedRoles && !in_array($this->_identity->getRoleID(),$this->_allowedRoles) && $this->getRequest()->getModuleName()!="login"){
                $this->_redirect($this->view->url(array('module'=>'login'),'default',true));
            }
        }



    }

    public function _initLoginForm()
    {
        $login_form = new Login_Form_Login();
        $login_form->referrer_url->setValue($this->view->url());
        $login_form->setAction($this->view->url(array('module'=>'login','controller'=>'index','action'=>'index'),'default',true));
        $this->view->loginForm = $login_form;
    }

}