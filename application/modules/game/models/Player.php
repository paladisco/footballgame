<?php
class Game_Model_Player
{
    private $_info;
    private $_stats;
    private $_active;
    private $_possession;

    public function __construct($player_id){
        $playerModel = new Game_Model_DbTable_Player();
        $this->_info = $playerModel->getEntry($player_id);

        $statModel = new Game_Model_DbTable_PlayerHasStat();
        $stats = $statModel->getStatsByPlayer($player_id);
        foreach($stats as $s){
            $this->_stats[$s['stat_id']] = $s['value'];
        }

        $this->_active = true;
    }

    public function getName(){
        return $this->_info['name'];
    }

    public function getPicture(){
        return 'http://graph.facebook.com/'.$this->_info['fb_uid'].'/picture';
    }

    public function getPosition(){
        return $this->_info['position'];
    }

    public function getCoordinates(){
        return array(
            'x'=>rand(10,90),
            'y'=>rand(20,80)
        );
    }

    public function getStatById($stat_id){
        return $this->_stats[$stat_id];
    }

    public function isActive(){
        return $this->_active;
    }

    public function getSkill(){
        $skill = $this->_stats[1];
        return $skill;
    }

    public function hasPossession(){
        if($this->_possession){
            return true;
        }else{
            return false;
        }
    }

    public function setPossession($possession){
        $this->_possession = $possession;
    }
}