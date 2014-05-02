<?php
class Game_Model_Match
{
    /**
     * @var $_teamInPossession Game_Model_Team
     */
    private $_teams;
    private $_minute;
    private $_status;
    private $_eventlog;
    private $_looplog;
    private $_possession;
    private $_playerInPossession;
    private $_standing;

    private $_offenseInstruction;
    private $_defenseInstruction;
    private $_situationType;
    private $_skillModifier;

    private $_ball;

    private $_running = false;
    private $_finished = false;

    private $_goals;

    private $_flip;
    private $_halftime = 1;
    private $_overtime = 0;

    const HOME_TEAM = 0;
    const AWAY_TEAM = 1;

    public function __construct($home_id,$away_id){
        $this->_status = "ready";
        $this->_eventlog = array();
        $this->_minute = 0;

        $homeTeam = new Game_Model_Team($home_id,self::HOME_TEAM);
        $awayTeam = new Game_Model_Team($away_id,self::AWAY_TEAM);

        $this->_teams = array(
            $homeTeam,
            $awayTeam
        );

        $this->_ball = array('x'=>50,'y'=>50);

        $this->_standing = array(0,0);
        $this->_logEvent("Match is about to start!");


    }

    public function getEventLog(){
        return $this->_eventlog;
    }

    public function getLoopLog(){
        return $this->_looplog;
    }

    private function _resetLoopLog(){
        $this->_looplog = array();
    }

    public function setOffenseInstruction($instruction,$skillModifier=50){
        $this->_offenseInstruction = $instruction;
        $this->_skillModifier = (int)$skillModifier;
    }

    public function setDefenseInstruction($instruction){
        $this->_defenseInstruction = $instruction;
    }

    public function getSummary(){
        $summary['standing'] = $this->getStanding();
        foreach($this->_goals as $goal){
            $summary['goals'][] = array(
                'minute' => $goal['minute'],
                'side' => $goal['side'],
                'player_name' => $goal['player']->getName(),
                'player_pic' => $goal['player']->getPicture()
            );
        }
        return $summary;
    }

    public function getPitch(){
        $pitch['ball'] = $this->_ball;
        if($this->getTeamInPossession()){
            $pitch['side'] = $this->getTeamInPossession()->getSide()+1;
            if($this->getPlayerInPossession()){
                $pitch['player'] = array(
                    'profile_pic' => $this->getPlayerInPossession()->getPicture(),
                    'coordinates' => $this->getPlayerInPossession()->getCoordinates()
                );
            }
        }
        return $pitch;
    }

    public function getSituation(){
        if($this->getTeamInPossession()){
            $situation['possession'] = $this->_possession;
            $situation['player'] = $this->getPlayerInPossession();
            $situation['type'] = $this->_situationType;
            if($this->_ball['x']>60){
                $situation['shoot'] = true;
            }
            if($this->_ball['x']<40){
                $situation['clear'] = true;
            }
            if($this->_ball['x']<40){
                $situation['clear'] = true;
            }
        }
        $situation['running'] = $this->isRunning();
        $situation['finished'] = $this->hasEnded();
        return $situation;
    }

    /**
     * @return Game_Model_Team
     */
    public function getHomeTeam(){
        return $this->getTeam(self::HOME_TEAM);
    }

    public function getHomeTeamColor(){
        $color = $this->getHomeTeam()->getPrimaryColor();
        return $color;
    }

    public function getAwayTeamColor(){
        $color = $this->getAwayTeam()->getPrimaryColor();
        if($color!=$this->getHomeTeamColor()){
            return $color;
        }else{
            return $this->getAwayTeam()->getSecondaryColor();
        }
    }

    /**
     * @return Game_Model_Team
     */
    public function getAwayTeam(){
        return $this->getTeam(self::AWAY_TEAM);
    }

    /**
     * @return string
     */
    public function getLineup(){
        return $this->getHomeTeam()->getName().' vs. '.$this->getAwayTeam()->getName();
    }

    /**
     * @return string
     */
    public function getStanding(){
        return $this->_standing[0].' : '.$this->_standing[1];
    }

    public function getGoals($side){
        return $this->_standing[$side];
    }

    public function getOutcome(){
        if($this->_standing[0]>$this->_standing[1]){
            return "1";
        }elseif($this->_standing[0]==$this->_standing[1]){
            return "X";
        }else{
            return "2";
        }
    }

    /**
     * @param int $key
     * @return Game_Model_Team
     */
    public function getTeam($key){
        return $this->_teams[$key];
    }

    /**
     * @return Game_Model_Team
     */
    public function getTeamInPossession(){
        return $this->_teams[$this->_possession];
    }

    /**
     * @return Game_Model_Team
     */
    public function getTeamInDefense(){
        return $this->_teams[($this->_possession+1)%2];
    }

    /**
     * @return Game_Model_Player
     */
    public function getPlayerInPossession(){
        return $this->_playerInPossession;
    }

    public function getShotDifficulty(){
        return rand($this->getDistanceToGoal(),100);
    }

    public function isRunning(){
        return $this->_running;
    }

    public function hasEnded(){
        return $this->_finished;
    }

    public function getDistanceToGoal(){
        if($this->_possession==HOME_TEAM){
            return 100-$this->_ball['x'];
        }else{
            return $this->_ball['x'];
        }
    }

    public function loop($resetLog=true){

        if($resetLog){
            $this->_resetLoopLog();
        }

        if($this->hasEnded()){
            return false;
        }

        if($this->_minute==0 && $this->_halftime==1){
            $this->_coinflip();
            if($this->_possession == self::HOME_TEAM){
                return true;
            }
        }

        if(!$this->isRunning() && !$this->hasEnded()){
            $this->_kickOff();
            if($this->_possession == self::HOME_TEAM){
                return true;
            }
        }

        $this->_minute++;

        // This is where the magic happens ;)
        if($this->isRunning()){

            if($this->_possession == self::AWAY_TEAM){
                $this->_randomOffenseInstruction();
            }

            switch($this->_offenseInstruction){
                case 'clear':
                    $this->_clear();
                    break;
                case 'pass':
                    $this->_pass();
                    break;
                case 'shoot':
                    $this->_shoot();
                    break;
                case 'score':
                    $this->_shoot('score');
                    break;
                case 'save':
                    $this->_shoot('save');
                    break;
                case 'miss':
                    $this->_shoot('miss');
                    break;
                case 'dribble':
                    $this->_dribble();
                    break;
                default:
                    $this->_losePossession();
                    break;
            }
        }


        // Check for Overtime at the end of each half
        if($this->_minute==45){
            $this->_rollOvertime();
        }

        if($this->_minute>=45+$this->_overtime){
            $this->_endHalftime();
        }

        if(count($this->getLoopLog())){
            if($this->_possession == self::AWAY_TEAM){
                $this->loop(false);
            }
            return true;
        }else{
            return $this->loop();
        }

    }

    private function _randomOffenseInstruction(){
        $roll = rand(0,100);
        $distance = $this->getDistanceToGoal();
        if($distance<40 && $roll>$distance*2)
        {
            $this->_offenseInstruction = 'shoot';
        }
        elseif($roll>40){
            if($roll>70 && $distance>60){
                $this->_offenseInstruction = 'clear';
            }else{
                $this->_offenseInstruction = 'pass';
            }
        }elseif($roll<=40){
            $this->_offenseInstruction = 'dribble';
        }
    }


    private function _advanceBall($length=null,$minlength=10,$maxlength=25){
        Local_DiceRoll::log("Advancing ball in favor of Team ".$this->getTeamInPossession()->getName());

        Local_DiceRoll::log("Ball moving from X ".$this->_ball['x']);
        if($this->_possession==0){
            $this->_ball['x']+=$length?$length:rand($minlength,$maxlength);
            $this->_ball['y']+=rand(-50,50);
        }elseif($this->_possession==1){
            $this->_ball['x']-=$length?$length:rand($minlength,$maxlength);
            $this->_ball['y']+=rand(-50,50);
        }
        Local_DiceRoll::log("To X ".$this->_ball['x']);

        if($this->_ball['y']>90){
            $this->_ball['y'] = 90;
        }
        if($this->_ball['x']>90){
            $this->_ball['x'] = 90;
        }
        if($this->_ball['y']<10){
            $this->_ball['y'] = 10;
        }
        if($this->_ball['x']<10){
            $this->_ball['x'] = 10;
        }
    }

    private function _rollOvertime(){
        $this->_overtime = rand(1,5);
    }

    private function _endHalftime(){
        $this->_halftime++;
        $this->_minute = 0;
        if($this->_halftime==2){
            $this->_logEvent('The Referee blows for half time.');
            if($this->_possession == $this->_flip){
                $this->_switchPossession();
            }
            $this->_stopMatch();
        }else{
            $this->_logEvent('That\'s it - the final whistle has blown.');
            $this->_logEvent('Final Score: '.$this->getStanding(),'score');
            $this->_stopMatch();
            $this->_endMatch();
        }
    }

    private function _stopMatch(){
        $this->_running = false;
    }

    private function _startMatch(){
        $this->_running = true;
    }

    private function _endMatch(){
        $this->_finished = true;
    }

    private function reassignPossession($player){
        if($this->getPlayerInPossession()){
            $this->getPlayerInPossession()->setPossession(false);
        }
        $this->_playerInPossession = $player;
        $this->getPlayerInPossession()->setPossession(true);
        if($this->getPlayerInPossession()->getPosition()==1){
            $this->_ball = array(
                'x' => $this->_possession==0?10:90,
                'y' => 50
            );
        }
    }

    // Attacker's Skill based Actions
    private function _dribble(){
        if($this->getPlayerInPossession()->getPosition()!=1){
            $message = $this->getPlayerInPossession()->getName().' dribbles around with the ball';
            Local_DiceRoll::log($message);

            if($defender = $this->getTeamInDefense()->getRandomFieldPlayerByDistance(100-$this->getDistanceToGoal())){

                $this->_advanceBall(10,20);
                Local_DiceRoll::log($this->getPlayerInPossession()->getName() ." dribble, roll for defender ".$defender->getName()." tackling");
                $skill = $defender->getSkill()/2;
                if(Local_DiceRoll::challenge(100,$skill+$this->getDistanceToGoal()/5)){
                    $this->_logEvent($message. ', as '.$defender->getName().' goes in for a tackling and wins posession!',null,true,true,$this->getTeamInDefense(),$defender);
                    $this->reassignPossession($defender);
                    $this->_switchPossession();
                    return;
                }
            }else{
                $this->_advanceBall(20,30);
                $this->_logEvent($message . ' is being completely left alone and moves onward!',null,true,true);
                return;
            }
        }else{
            return $this->_pass('success');
        }

        Local_DiceRoll::log("No defending, see if actual dribbling is successful");
        $skill = $this->getPlayerInPossession()->getSkill();
        if(Local_DiceRoll::challenge(100,$skill)){
            $this->_advanceBall(20,30);
            $this->_logEvent($message . ' and skillfully moves the ball forward!',null,true,true);
        }else{
            Local_DiceRoll::log("Fail dribbling");
            $message .= ', but loses the ball!';
            $this->_losePossession(null,false);
            $this->_logEvent($message . $this->getPlayerInPossession()->getName().' quickly takes hold of the ball!',null,true,true);
        }
    }

    private function _pass($action=null){
        if($this->getPlayerInPossession()->getPosition()==1){
            $this->_logEvent($this->getPlayerInPossession()->getName().' brings the ball back into play.',null,true,true);
            $action = 'success';
        }else{
            $message = $this->getPlayerInPossession()->getName().' passes the ball';
        }
        Local_DiceRoll::log($this->getPlayerInPossession()->getName() ." passing, roll to see if the pass finds its destination");
        $skill = ($this->getPlayerInPossession()->getSkill()/2)+($this->_skillModifier/2);
        $receiver = $this->getTeamInPossession()->getRandomFieldPlayerByDistance($this->getDistanceToGoal());
        if(Local_DiceRoll::challenge(100,$skill) || $action=='success'){
            $this->_advanceBall(null,10,20);
            Local_DiceRoll::log("Roll to see if ".$receiver->getName()." can take posession");
            $skill = $receiver->getSkill()+$this->getDistanceToGoal()/5;
            if(Local_DiceRoll::challenge(100,$skill) || $action=='success'){
                $this->reassignPossession($receiver);
                $this->_advanceBall(null,10,20);
                $this->_logEvent($message . ' to ' . $this->getPlayerInPossession()->getName().', who successfully claims the ball.',null,true,true);
            }else{
                $this->_logEvent($message . ' to ' . $receiver->getName().', who tries to receive the sweet pass, but fails miserably.',null,true,true);
                $this->_losePossession();
            }
        }else{
            $message .= ', but instead playing it to the opponent, such failure! ' . $receiver->getName().' can just shake his head. ';
            $this->_losePossession(null,false,$message);
        }
    }

    private function _clear(){

        Local_DiceRoll::log($this->getPlayerInPossession()->getName()." clearing the ball");

        if($this->getPlayerInPossession()->getPosition()==1){
            $message = $this->getPlayerInPossession()->getName().' kicks the ball far up field';
        }else{
            $message = $this->getPlayerInPossession()->getName().' clears the ball wide up field';
        }

        $this->_advanceBall(null,40,60);
        Local_DiceRoll::log("Roll to see if Team can keep/get the ball ".$this->getPlayerInPossession()->getName());
        $skill = ($this->getPlayerInPossession()->getSkill()/2)+($this->_skillModifier/2);
        if(Local_DiceRoll::challenge(100,$skill)){
            $this->reassignPossession($this->getTeamInPossession()->getRandomFieldPlayerByDistance($this->getDistanceToGoal()));
            $this->_logEvent($message . ' where ' . $this->getPlayerInPossession()->getName().' successfully claims the ball.',null,true,true);
        }else{
            $this->_losePossession(null,false,$message.' but no one there to take the ball, ');
        }
    }

    private function _shoot($action=null){
        $this->_logEvent($this->getPlayerInPossession()->getName().' takes aim and shoots...',null,true,true);
        $difficulty=$this->getShotDifficulty();
        Local_DiceRoll::log("Roll to see if shot even goes on goal");
        $skill = ($this->getPlayerInPossession()->getSkill()/2)+($this->_skillModifier/2);
        if(Local_DiceRoll::challenge($difficulty,$skill) || ($action && $action!='miss')){
            $this->_shootOnGoal($action);
        }else{
            $this->_logEvent($this->getPlayerInPossession()->getName().' completely misses the Target. What a waster!',null,true,true);
            $this->_losePossession(1,false);
            $this->_logEvent('It comes for a goal kick!',null,true,true,null,null,'miss');
        }
    }

    private function _shootOnGoal($action=null){
        if($goalkeeper = $this->getTeamInDefense()->getPlayerByPosition(1)){
            Local_DiceRoll::log("Test goalkeeper's skills");
            $skill = $goalkeeper->getSkill();
            if((Local_DiceRoll::challenge(100,$skill) && $action!='score') || $action=='save'){
                $this->reassignPossession($goalkeeper);
                $this->_losePossession(1,false);
                $this->_logEvent($goalkeeper->getName().' saves!',null,true,true,null,null,'save');
            }else{
                $this->_logEvent($goalkeeper->getName().' can\'t reach the ball anymore! It hits the back of the net!',null,true,true,$this->getTeamInDefense(),$goalkeeper,'score');
                $this->_goal();
            }
        }else{
            $this->_logEvent('The ball goes straight for the empty goal!');
            $this->_goal();
        }

    }

    private function _goal(){
        $this->_logEvent($this->getPlayerInPossession()->getName().' scores!','large',true,true);
        $this->_standing[$this->_possession]++;
        $this->_goals[] = array(
            'minute' => $this->_minute,
            'team' => $this->getTeamInPossession(),
            'side' => $this->_possession,
            'player' => $this->getPlayerInPossession()
        );
        $this->_logEvent($this->getStanding(),'score');
        $this->_losePossession(null,false);
        $this->_stopMatch();
    }

    private function _switchPossession(){
        $this->_possession = ($this->_possession + 1) % 2;
        $this->_situationType = ($this->_possession?'defense':'offense');
    }

    private function _losePossession($toPosition=null,$commented=true,$message=''){
        Local_DiceRoll::log("Automatic losing posession happening (commented: ".$commented.")");
        if($commented){
            $message .= $this->getPlayerInPossession()->getName().' loses possession, ';
        }
        $this->_switchPossession();
        if($player = $this->getTeamInPossession()->getPlayerByPosition($toPosition)){
            $this->reassignPossession($player);
        }else{
            $this->reassignPossession($this->getTeamInPossession()->getRandomFieldPlayerByDistance($this->getDistanceToGoal()));
        }
        if($commented || $message){
            $this->_logEvent($message . $this->getPlayerInPossession()->getName().' has got the ball now.',null,true,true);
        }
    }

    private function _coinflip(){
        $this->_flip = rand(0,1);
        $this->_possession = $this->_flip;
        $this->_switchPossession();
        $this->_logEvent($this->getTeam($this->_possession)->getName().' wins the Coin Toss!',null,true);
        $this->_kickOff();
    }

    private function _kickOff(){
        $this->_ball = array('x'=>50,'y'=>50);
        $this->_startMatch();
        $this->reassignPossession($this->getTeamInPossession()->getRandomAttacker());
        $this->_logEvent($this->getTeamInPossession()->getName().' kicks off. '.$this->getPlayerInPossession()->getName().' is on the ball.',null,true,true);
    }

    /**
     * @param string $message
     * @param string $class
     * @param bool $showTeamPic
     * @param bool $showPlayerPic
     * @param Game_Model_Team $team
     * @param Game_Model_Player $player
     */
    private function _logEvent($message,$class=null,$showTeamPic=false,$showPlayerPic=false,$team=null,$player=null,$action=null){
        $minuteStr = min((($this->_halftime-1)*45)+$this->_minute,$this->_halftime*45);
        if($this->_minute>45){
            $minuteStr .= ' +'.$this->_minute%45;
        }
        $event = array(
            'minute' => $minuteStr,
            'message' => $message,
            'action' => $action,
            'class' => $class,
            'ball' => $this->_ball
        );
        if($team instanceof Game_Model_Team){
            $event['team_pic'] = $team->getPicture();
            $event['side'] = $team->getSide()+1;
        }elseif($showTeamPic){
            $event['team_pic'] = $this->getTeamInPossession()->getPicture();
            $event['side'] = $this->getTeamInPossession()->getSide()+1;
        }else{
            $event['side'] = false;
        }
        if($player instanceof Game_Model_Player){
            $event['player_pic'] = $player->getPicture();
        }elseif($showPlayerPic){
            $event['player_pic'] = $this->getPlayerInPossession()->getPicture();
        }
        $this->_eventlog[] = $event;
        $this->_looplog[] = $event;
    }

}