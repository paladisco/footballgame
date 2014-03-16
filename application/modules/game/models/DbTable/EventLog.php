<?php
class Game_Model_DbTable_EventLog extends RF_Model_Regular
{
    protected $_name = 'fb_event_log';

    public function getByTeam($team_id){
        $select = $this->select(true)
            ->setIntegrityCheck(false)
            ->join(array('e'=>'fb_event'),'e.id=fb_event_log.event_id')
            ->where('team_id='.(int)$team_id)
            ->order('timestamp DESC');
        if($log = $this->fetchAll($select)){
            return $log->toArray();
        }
    }

    public function getLatestByTeam($team_id){
        $select = $this->select(true)
            ->setIntegrityCheck(false)
            ->join(array('e'=>'fb_event'),'e.id=fb_event_log.event_id')
            ->where('team_id='.(int)$team_id)
            ->order('timestamp DESC')
            ->limit(1);
        if($event = $this->fetchRow($select)){
            return $event->toArray();
        }
    }
}