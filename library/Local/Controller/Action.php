<?php
require 'vendor/facebook/facebook.php';
class Local_Controller_Action extends Zend_Controller_Action
{
	protected $_storage;
    protected $_facebook_user_id;
    protected $_facebook_api;
    protected $_facebook_page = 'RACERFISH';

    public $team;

	public function init(){

        $this->_storage = new Zend_Session_Namespace(APP_ID);

        $this->_facebook_api = new Facebook(array(
            'appId'  => FB_APP_ID,
            'secret' => FB_APP_SECRET,
        ));

        // Get User ID
        $this->_facebook_user_id = $this->_facebook_api->getUser();

        $this->view->facebook_app_id = $this->_facebook_app_id;
        $this->view->facebook_page = $this->_facebook_page;

        if ($this->_facebook_user_id) {
            $this->view->facebook_user_id = $this->_facebook_user_id;
            $teamModel = new Game_Model_DbTable_Team();
            $this->team = $teamModel->getEntryByUser($this->_facebook_user_id);
            $this->view->logoutUrl = $this->_facebook_api->getLogoutUrl();
        } else {
            if($this->getRequest()->getControllerName()!='index' ||
                ($this->getRequest()->getControllerName()=='index' && $this->getRequest()->getActionName()!='index')){
                $this->_redirect($this->view->url(array('module'=>'game'),'default',true));
            }
            $this->view->loginUrl = $this->_facebook_api->getLoginUrl();
        }
	}
}