<?php
class Game_MatchController extends Local_Controller_Action
{
    /**
     * @var $_match Game_Model_Match
     */
    private $_match;

    public function init(){
        parent::init();
        if($this->getRequest()->getParam('reset')){
            unset($this->_storage->match);
            $this->_redirect($this->view->url(array('reset'=>null)));
        }
        if($this->_storage->match){
            $this->_match = $this->_storage->match;
        }else{
            $this->_match = null;
        }
    }

    public function indexAction()
    {
        if(!$this->_storage->match){
            $homeTeamId = $this->team['id'];
            $awayTeamId = $this->getRequest()->getParam('away_team_id');
            $match = new Game_Model_Match($homeTeamId,$awayTeamId);
            $this->_storage->match = $match;
        }else{
            $match = $this->_match;
        }

        $this->view->title = "Match Time";
        $this->view->headline = $match->getLineup();
        $this->view->log = $match->getEventLog();

        $this->view->summary = $match->getSummary();
        $this->view->pitch = $match->getPitch();
        $this->view->situation = $match->getSituation();
        $this->view->homeTeam = $match->getHomeTeam();
        $this->view->awayTeam = $match->getAwayTeam();
    }

    public function playAction(){

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        if($this->_match){

            $instruction = $this->getRequest()->getParam('instruction');

            $this->_match->setOffenseInstruction($instruction);
            $this->_match->loop();

            $summary = $this->_match->getSummary();
            $log = $this->_match->getLoopLog();
            $situation = $this->_match->getSituation();

            foreach($log as $event){
                $html[] = $this->view->partial('match/_event.phtml',array('event'=>$event,'display'=>'style="display: none;"'));
            }

            $return = array(
                'summary' => $this->view->partial('match/_summary.phtml',array('summary'=>$summary)),
                'controls' => $this->view->partial('match/_controls.phtml',array('situation'=>$situation)),
                'html' => $html,
                'log' => $log
            );

            echo json_encode($return);

        }

        $this->_storage->match = $this->_match;

    }

    public function skillgameAction(){

        $this->_helper->layout->disableLayout();

        if($this->_match){

            $this->view->ball = array('x'=>rand(10,90).'%','y'=>rand(75,85).'%');
            $this->view->keeper = $this->_match->getTeam(Game_Model_Match::AWAY_TEAM)->getPlayerByPosition(1);
            $this->view->player = $this->_match->getPlayerInPossession();
            $this->view->difficulty = $this->_match->getShotDifficulty();

        }
    }

}

