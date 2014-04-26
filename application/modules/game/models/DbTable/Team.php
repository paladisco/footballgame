<?php
class Game_Model_DbTable_Team extends RF_Model_Regular
{
    protected $_name = 'fb_team';
    protected $_imagePath = 'team/';

    public function insertEntry($f,$user){
        $f['founded'] = time();
        $f['formation_id'] = 1;
        $f['fb_uid'] = $user['id'];
        $f['fb_name'] = $user['name'];
        $pic = json_decode(file_get_contents('https://graph.facebook.com/'.$user['username'].'/picture?redirect=false'),true);
        $f['profile_pic'] = $pic['data']['url'];
        parent::insertEntry($f);
    }

    public function getEntryByUser($fb_user_id){
        if($result = $this->fetchRow('fb_uid="'.$fb_user_id.'"')){
            $playerModel = new Game_Model_DbTable_Player();
            $formationModel = new Game_Model_DbTable_Formation();
            $team = $result->toArray();
            $team['players'] = $playerModel->getByTeam($team['id']);
            $team['formation'] = $formationModel->getEntry($team['formation_id']);
            return $team;
        }else{
            return false;
        }
    }
}