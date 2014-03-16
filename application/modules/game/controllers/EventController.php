<?php
class Game_EventController extends Local_Controller_Action
{
    /**
     * @var $_event Game_Model_Event
     */
    private $_event;
    public function init(){
        parent::init();
        $this->_event = new Game_Model_Event();
    }

    public function indexAction()
    {
        $logModel = new Game_Model_DbTable_EventLog();
        $this->view->log = $logModel->getByTeam($this->team['id']);
    }

    public function latestAction(){
        $this->_helper->layout->disableLayout();

        $logModel = new Game_Model_DbTable_EventLog();
        $this->view->event = $logModel->getLatestByTeam($this->team['id']);
    }

    public function triggerAction()
    {
        $event_id = $this->_getParam('event_id');
        $this->_event->triggerRandomEvent($this->team['id'],$event_id);
        $this->_redirect($this->view->url(array('action'=>'latest')));
    }

}

