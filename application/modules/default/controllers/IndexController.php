<?php
class IndexController extends Local_Controller_Backend
{
    public function indexAction()
    {
        $this->_redirect($this->view->url(array('module'=>'game','controller'=>'index', 'action'=>'index'),'default',true));
    }
}

