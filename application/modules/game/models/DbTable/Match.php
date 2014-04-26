<?php
class Game_Model_DbTable_Match extends RF_Model_Regular
{
    protected $_name = 'fb_match';

    public function hasOpenMatch($home_id,$away_id){
        $select = $this->select(false)
            ->from(array('m'=>'fb_match'))
            ->columns('id AS match_id')
            ->setIntegrityCheck(false)
            ->join(array('t'=>'fb_team'),'m.away_id=t.id')
            ->where('home_id='.(int)$home_id.' AND away_id='.(int)$away_id.' AND status=1');

        if($match = $this->fetchRow($select)){
            return $match;
        }else{
            return false;
        }
    }

    public function getOngoingMatches($home_id){
        $select = $this->select(false)
            ->from(array('m'=>'fb_match'))
            ->columns('id AS match_id')
            ->setIntegrityCheck(false)
            ->join(array('t'=>'fb_team'),'m.away_id=t.id')
            ->where('home_id='.(int)$home_id.' AND status=1');

        if($matches = $this->fetchAll($select)){
            return $matches->toArray();
        }
    }

    public function initMatch($home_id,$away_id,$state){
        $data = array(
            'home_id' => $home_id,
            'away_id' => $away_id,
            'status' => 1,
            'state' => serialize($state)
        );
        return $this->insertEntry($data);
    }

    public function getState($match_id){
        $match = $this->getEntry($match_id);
        return unserialize($match['state']);
    }

    public function saveState($match_id,$state){
        $data = array(
            'state' => serialize($state)
        );
        $this->updateEntry($data,$match_id);
    }

    /**
     * @param $match_id
     * @param $state Game_Model_Match
     */
    public function saveMatch($match_id,$state){
        $data = array(
            'end_result' => $state->getOutcome(),
            'home_goals' => $state->getGoals($state::HOME_TEAM),
            'away_goals' => $state->getGoals($state::AWAY_TEAM),
            'status' => 2
        );
        $this->updateEntry($data,$match_id);
    }
}