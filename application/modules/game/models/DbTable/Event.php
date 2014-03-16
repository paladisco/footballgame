<?php
class Game_Model_DbTable_Event extends RF_Model_Regular
{
    protected $_name = 'fb_event';
    protected $_imagePath = 'event/';

    public function getEffects($event_id){
        $eventEffectModel = new Game_Model_DbTable_EventEffect();
        if($effects = $eventEffectModel->fetchAll('event_id='.(int)$event_id)){
            return $effects->toArray();
        }
    }
}