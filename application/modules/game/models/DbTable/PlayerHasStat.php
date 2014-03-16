<?php
class Game_Model_DbTable_PlayerHasStat extends RF_Model_Regular
{
    protected $_name = 'fb_player_has_stat';
    protected $_primary = array('player_id','stat_id');

    public function getStatsByPlayer($player_id){
        $select = $this->select(false)
            ->setIntegrityCheck(false)
            ->from(array('st'=>'fb_player_stat'),array('name','type'))
            ->join(array('phs'=>'fb_player_has_stat'),'phs.stat_id=st.id')
            ->where('phs.player_id='.(int)$player_id);
        $result = $this->fetchAll($select)->toArray();
        return $result;
    }

}