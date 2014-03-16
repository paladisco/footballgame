<?php
class Login_IndexController extends Local_Controller_Backend
{
    public function indexAction()
    {
        $auth = $this->_auth;
        // If we're already logged in, just redirect
        if($auth->hasIdentity())
        {
            $this->_redirect($this->getRequest()->getPost('referrer_url'));
        }

        $request = $this->getRequest();

        if($request->isPost())
        {
            if($this->view->loginForm->isValid($request->getPost()))
            {
                try{
                    if($this->_identity->authenticate($auth,$this->view->loginForm->getValues())){
                        $this->_redirect($this->view->loginForm->getValue('referrer_url'));
                    }
                }catch(Exception $e){
                    $this->view->errorMessage = $e->getMessage();
                }
            }
        }
    }

    public function logoutAction()
    {
        //	    $loginModel = new User_Model_DbTable_Login();
        //		$loginModel->logout($this->_identity->ad_id);

        Zend_Auth::getInstance()->clearIdentity();
        $this->_identity->destroyIdentity();

        $this->_redirect($this->view->url(array('module'=>'default','controller'=>'index'),'default',true));
    }

}
