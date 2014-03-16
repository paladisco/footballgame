<?php
class Game_IndexController extends Local_Controller_Action
{

    public function indexAction()
    {
        if($this->team){
            $this->_redirect($this->view->url(array('action'=>'dashboard')));
        }elseif($this->_facebook_user_id){
            $this->_redirect($this->view->url(array('controller'=>'setup')));
        }
    }

    public function dashboardAction(){

        $this->view->title = 'Dashboard';
        $this->view->headline = 'Alles auf einen Blick';

        $playerModel = new Game_Model_DbTable_Player();
        $this->view->team = $this->team;
        $this->view->formation = $playerModel->getFormationByTeam($this->team['id']);
    }

    public function rankingAction(){
        $friends = $this->_facebook_api->api('/'.$this->_facebook_user_id.'/friends?fields=installed');
        $teamModel = new Game_Model_DbTable_Team();
        $return = array();
        $playerModel = new Game_Model_DbTable_Player();
        foreach($friends['data'] as $f){
            if($team = $teamModel->getEntryByUser($f['id'])){
                $players = $playerModel->fetchAll('position!=0 AND team_id='.(int)$team['id']);
                if(count($players)){
                    $return[] = $team;
                }
            }
        }
        $this->view->friends = $return;

    }

    public function refreshAction(){
        $this->_helper->viewRenderer->setNoRender(true);

        $playerModel = new Game_Model_DbTable_Player();
        $players = $playerModel->getByTeam($this->team['id']);
        foreach($players as $p){
            $playerModel->refreshImage($p);
        }
    }


}

