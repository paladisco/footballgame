<?php
class Game_Model_Event
{
    /**
     * @var $_eventModel Game_Model_DbTable_Event
     */
    private $_info;
    private $_log;
    private $_eventModel;

    public function __construct(){
        $this->_eventModel = new Game_Model_DbTable_Event();
    }

    private function _applyEffects(&$effects,$where = 1){
        foreach($effects as &$effect){
            $effectModel = new Game_Model_DbTable_EventEffect();
            $value = explode(',',$effect['value']);
            if(count($value)>1){
                $value = rand($value[0],$value[1]);
            }else{
                $value = $effect['value'];
            }
            if($effect['delta']){
                $data = array($effect['ref_field'] => new Zend_Db_Expr($effect['ref_field'].'+'.(int)$value));
            }else{
                $data = array($effect['ref_field'] => $value);
            }
            $effectModel->getAdapter()
                 ->update('fb_'.$effect['ref_table'],$data,$where.' AND '.$effect['where']);
            $effect['log'] = array($value,$where.' AND '.$effect['where']);
        }
    }

    public function triggerRandomEvent($team_id){

        $event = $this->_eventModel->getRandomEntry();

        switch($event['type']){
            case 'player':
                $playerModel = new Game_Model_DbTable_Player();
                $player = $playerModel->getRandomEntry('team_id='.(int)$team_id);
                $logdata = $this->triggerPlayerEvent($event,$player);
                break;
        }
        $eventLogModel = new Game_Model_DbTable_EventLog();
        $eventLogModel->insert($logdata);
    }

    public function triggerPlayerEvent($event,$player){

        $playerModel = new Game_Model_DbTable_Player();

        $where = 'player_id='.(int)$player['id'];
        $logtext = str_replace('###PLAYERNAME###',$player['name'],$event['description']);
        $logpic = 'http://graph.facebook.com/'.$player['fb_uid'].'/picture';

        $statsBefore = $playerModel->getStats($player);
        $effects = $this->_eventModel->getEffects($event['id']);
        $this->_applyEffects($effects,$where);
        $statsAfter = $playerModel->getStats($player);

        $logdata = array(
            'event_id' => $event['id'],
            'team_id' => $player['team_id'],
            'timestamp' => time(),
            'pic' => $logpic,
            'logtext' => $logtext,
            'effects' => json_encode($effects),
            'before' => json_encode($statsBefore),
            'after' => json_encode($statsAfter)
        );
        return $logdata;
    }
}