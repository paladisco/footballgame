<?php
class Game_PlayerController extends Local_Controller_Action
{
    /**
     * @var $_model Game_Model_DbTable_Player
     */
    protected $_model = null;

    public function init(){
        parent::init();
        $this->_model = new Game_Model_DbTable_Player();
    }

    public function indexAction()
    {

    }

    public function addAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        if($this->getRequest()->isPost()){
            $f = $this->getRequest()->getPost();
            $player_id = $this->_model->insertEntry($f['friend_id'],$f['friend_name'],$this->team['id']);
            $stats = $this->_storage->newPlayerStats;
            $this->_model->addStats($player_id,$stats);
            $this->_redirect($this->view->url(array('controller'=>'team','action'=>'index')));
        }
    }

    public function loadFriendsAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        $friends = $this->_facebook_api->api('/'.$this->_facebook_user_id.'/friends');
        $return = array();
        foreach($friends['data'] as $f){
            $return[] = array('value'=>$f['name'],'id'=>$f['id'],'tokens'=>explode(" ",$f['name']));
        }
        echo json_encode($return);
    }

    public function updatePositionAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        if($position = json_decode($this->_getParam('position'))){
            $this->_model->update(array('position'=>0),'team_id='.(int)$this->team['id']);
            foreach($position as $pos => $player_id){
                if($pos && $player_id){
                    $this->_model->update(array(
                        'position' => $pos
                    ),'id='.(int)$player_id);
                }
            }
        }
    }

    public function healAction(){
        if($player_id = $this->_getParam('player_id')){
            $eventDBModel = new Game_Model_DbTable_Event();
            $event = $eventDBModel->getEntry(4);
            $player = $this->_model->getEntry($player_id);

            $eventModel = new Game_Model_Event();
            $logdata = $eventModel->triggerPlayerEvent($event,$player);
            $eventLogModel = new Game_Model_DbTable_EventLog();
            $eventLogModel->insert($logdata);
        }
        $this->_redirect($this->view->url(array('controller'=>'event','action'=>'latest')));
    }

    public function deleteAction(){
        if($id = $this->_getParam('id')){
            $this->_model->deleteEntry($id);
        }
    }
}

