<?php
class Game_TeamController extends Local_Controller_Action
{
    public function indexAction()
    {
        $this->view->title = 'Team Manager';
        $this->view->headline = 'Add and remove players from your roster';

        $this->view->team = $this->team;

        if($friend_id = $this->getRequest()->getParam('friend_id')){
            $playerModel = new Game_Model_DbTable_Player();
            $this->view->friend = $this->_facebook_api->api('/'.$friend_id);
            $newPlayerStats = $playerModel->rollNewPlayer();
            $this->view->stats = $newPlayerStats;
            $this->_storage->newPlayerStats = $newPlayerStats;
        }

        $friends = $this->_facebook_api->api('/'.$this->_facebook_user_id.'/friends');
        $return = array();
        foreach($friends['data'] as $f){
            $return[] = array('name'=>$f['name'],'id'=>$f['id']);
        }
        $this->view->friends = $return;
    }

    public function trainingAction(){
        $type = $this->_getParam('type');

        switch($type){
        }
    }

}

