<?php
class Game_Model_Team
{
    private $_players;
    private $_info;
    private $_formation;
    private $_side;
    private $_lines;

    public function __construct($team_id,$side){
        $teamModel = new Game_Model_DbTable_Team();
        $this->_info = $teamModel->getEntry($team_id);

        $this->_side = $side;

        $playerModel = new Game_Model_DbTable_Player();
        $players = $playerModel->getByTeam($team_id);
        foreach($players as $p){
            $this->_players[$p['position']] = new Game_Model_Player($p['id']);
        }

        $formationModel = new Game_Model_DbTable_Formation();
        $formation = $formationModel->getEntry($this->_info['formation_id']);

        $lines = explode('-',$formation['name']);
        $pos = 11;

        foreach($lines as $line){
            for($i=0;$i<$line;$i++){
                $this->_lines[$line][] = $this->_players[$pos];
                $pos--;
            }
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

    private function _getRandomPlayerByLine($line,$noPossession=true){
        $player = array_rand($this->_lines[$line]);
        if($player){
            if($player->isActive() && ($noPossession?!$player->hasPossession():1)){
                return $player;
            }
        }
        return $this->getRandomPlayerByLine($line,$noPossession);
    }

    public function getRandomDefender($noPossession=true){
        return $this->_getRandomPlayerByLine(0,$noPossession);
    }
    public function getRandomMidfielder($noPossession=true){
        return $this->_getRandomPlayerByLine(1,$noPossession);
    }
    public function getRandomAttacker($noPossession=true){
        return $this->_getRandomPlayerByLine(2,$noPossession);
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
        return $this->getRandomFieldPlayer($noPossession);
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