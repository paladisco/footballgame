<?php
class Game_Model_DbTable_Player extends RF_Model_Regular
{
    protected $_name = 'fb_player';
    protected $_imagePath = 'player/';

    public function getFormationByTeam($team_id){
        $res = $this->getByTeam($team_id);
        foreach($res as $r){
            if($r['position']!=0){
                $formation[$r['position']] = $r;
            }
        }
        return $formation;
    }

    public function getStats($r){
        $statModel = new Game_Model_DbTable_PlayerHasStat();
        $stats = $statModel->getStatsByPlayer($r['id']);
        foreach($stats as $s){
            $r['stats'][$s['type']][$s['stat_id']] = array('name'=>$s['name'],'points'=>$s['value']);
            if($s['name']=='Fitness' && $s['value']<20){
                $r['injured'] = true;
            }
        }
        return $r;
    }

    public function getSimpleStatArray($id){
        $statModel = new Game_Model_DbTable_PlayerHasStat();
        $stats = $statModel->getStatsByPlayer($id);
        foreach($stats as $s){
            $r[$s['stat_id']] = $s['value'];
        }
        return $r;
    }

    public function getByTeam($team_id){
        $res = $this->fetchAll('team_id='.(int)$team_id)->toArray();
        foreach($res as &$r){
            $r = $this->getStats($r);
        }
        return $res;
    }

    public function rollNewPlayer(){
        $basePoints = 10;
        $poolPoints = 100;
        $statModel = new Game_Model_DbTable_PlayerStat();
        $stats = $statModel->fetchAll('type="primary"')->toArray();
        foreach($stats as $stat){
            $allocPoints = min(rand(0,80),$poolPoints);
            $statPoints['primary'][$stat['id']] = array ('name'=>$stat['name'],'points'=>$basePoints+$allocPoints);
            $poolPoints -= $allocPoints;
        }
        if($poolPoints>0){
            end($statPoints['primary']);
            $statPoints['primary'][key($statPoints['primary'])]['points'] += $poolPoints;
        }
        $statPoints['fitness'][3]['points'] = rand(90,100);
        return $statPoints;
    }

    public function insertEntry($friend_id,$name,$team_id){
        $f['fb_uid'] = $friend_id;
        $f['name'] = $name;
        $f['team_id'] = $team_id;
        $pic = json_decode(file_get_contents('https://graph.facebook.com/'.$friend_id.'/picture?redirect=false'),true);
        $f['profile_pic'] = $pic['data']['url'];
        $f['position'] = 'Free';
        return parent::insertEntry($f);
    }

    public function refreshImage($p){
        $pic = json_decode(file_get_contents('https://graph.facebook.com/'.$p['fb_uid'].'/picture?type=normal&redirect=false'),true);
        $f['profile_pic'] = $pic['data']['url'];
        $this->updateEntry($f,$p['id']);
    }

    public function addStats($player_id,$stats){
        $playerHasStatModel = new Game_Model_DbTable_PlayerHasStat();
        foreach($stats as $typestats){
            foreach($typestats as $stat_id => $stat){
                $playerHasStatModel->insert(array(
                    'player_id' => $player_id,
                    'stat_id' => $stat_id,
                    'value' => $stat['points']
                ));
            }
        }
    }
}