<?php
class Game_Model_Team
{
    private $_players;
    private $_info;
    private $_formation;
    private $_side;

    public function __construct($team_id,$side,$formation='4-4-2'){
        $teamModel = new Game_Model_DbTable_Team();
        $this->_info = $teamModel->getEntry($team_id);

        $this->_side = $side;

        $playerModel = new Game_Model_DbTable_Player();
        $players = $playerModel->getByTeam($team_id);
        foreach($players as $p){
            $this->_players[$p['position']] = new Game_Model_Player($p['id']);
        }

        $lines = explode('-',$formation);
        $pos = 2;
        foreach($lines as $line){
            $pos += $line-1;
            $this->_formation[] = $pos;
        }
    }

    public function getName(){
        return $this->_info['name'];
    }

    public function getPicture(){
        return $this->_info['profile_pic'];
    }

    public function getSide(){
        return $this->_side;
    }
    /**
     * @param bool $noPossession
     * @return object Game_Model_Player
     */
    public function getRandomFieldPlayer($noPossession=true){
        $player = $this->_players[rand(2,11)];
        if($player){
            if($player->isActive() && ($noPossession?!$player->hasPossession():1)){
                return $player;
            }
        }
        return $this->getRandomFieldPlayer();
    }

    /**
     * @param int $position
     * @return onject Game_Model_Player
     */
    public function getPlayerByPosition($position){
        if($player = $this->_players[$position]){
            return $player;
        }else{
            return $this->getRandomFieldPlayer();
        }
    }
}