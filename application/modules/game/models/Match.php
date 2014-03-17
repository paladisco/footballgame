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

    public function setOffenseInstruction($instruction){
        $this->_offenseInstruction = $instruction;
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
        if($this->_possession==0){
            return 100-$this->_ball['x'];
        }else{
            return $this->_ball['x'];
        }
    }

    public function loop(){

        $this->_resetLoopLog();

        if($this->hasEnded()){
            return false;
        }

        if($this->_minute==0 && $this->_halftime==1){
            $this->_coinflip();
        }

        if(!$this->isRunning() && !$this->hasEnded()){
            $this->_kickOff();
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
            return true;
        }else{
            return $this->loop();
        }

    }

    private function _randomOffenseInstruction(){
        $roll = rand(0,100);
        if($this->getDistanceToGoal()<50 && $roll>$this->getDistanceToGoal())
        {
            $this->_offenseInstruction = 'shoot';
        }
        elseif($roll>70){
            $this->_offenseInstruction = 'pass';
        }elseif($roll>40){
            $this->_offenseInstruction = 'dribble';
        }
    }


    private function _advanceBall($length=null,$minlength=10,$maxlenght=25){
        if($this->_possession==0){
            $this->_ball['x']+=$length?$length:rand($minlength,$maxlenght);
            $this->_ball['y']+=rand(-50,50);
        }elseif($this->_possession==1){
            $this->_ball['x']-=$length?$length:rand($minlength,$maxlenght);
            $this->_ball['y']+=rand(-50,50);
        }

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

    // Attacker's Actions
    private function _dribble(){
        if($this->getPlayerInPossession()->getPosition()!=1){
            $this->_logEvent($this->getPlayerInPossession()->getName().' dribbles around with the ball.',null,true,true);
            $defender = $this->getTeamInDefense()->getRandomFieldPlayer();
            $roll = rand(0,100);
            if($defender->getSkill()>$roll){
                $this->_logEvent($defender->getName().' goes in for a tackling and wins posession!',null,true,true,$this->getTeamInDefense(),$defender);
                $this->reassignPossession($defender);
                $this->_switchPossession();
                return;
            }
        }else{
            return $this->_pass('success');
        }
        $roll=rand(0,100);
        if($roll<=$this->getPlayerInPossession()->getSkill()/3){
            $this->_logEvent('Unbelievable! '.$this->getPlayerInPossession()->getName().' loses the ball!',null,true,true);
            $this->_losePossession(null,false);
            $this->_logEvent($this->getPlayerInPossession()->getName().' quickly takes hold of the ball!',null,true,true);

        }else{
            $this->_advanceBall(15);
            $this->_logEvent('Nice work! '.$this->getPlayerInPossession()->getName().' skillfully moves the ball forward!',null,true,true);
        }
    }

    private function _pass($action=null){
        if($this->getPlayerInPossession()->getPosition()==1){
            $this->_logEvent($this->getPlayerInPossession()->getName().' brings the ball back into play.',null,true,true);
        }else{
            $this->_logEvent($this->getPlayerInPossession()->getName().' attempts a pass.',null,true,true);
        }
        $roll=rand(0,100);
        if($roll<=$this->getPlayerInPossession()->getSkill() || $action=='success'){
            $this->reassignPossession($this->getTeamInPossession()->getRandomFieldPlayer());
            $this->_advanceBall(null,20,30);
            $this->_logEvent($this->getPlayerInPossession()->getName().' successfully claims the pass.',null,true,true);
        }else{
            $this->_losePossession();
        }
    }

    private function _clear(){
        if($this->getPlayerInPossession()->getPosition()==1){
            $this->_logEvent($this->getPlayerInPossession()->getName().' kicks the ball far up field into play.',null,true,true);
        }else{
            $this->_logEvent($this->getPlayerInPossession()->getName().' clears the ball.',null,true,true);
        }
        $roll=rand(0,100);
        $this->_advanceBall(rand(40,60)-abs(50-(50-$this->_ball['x'])));
        if($roll<=50){
            $this->reassignPossession($this->getTeamInPossession()->getRandomFieldPlayer());
            $this->_logEvent($this->getPlayerInPossession()->getName().' successfully claims the ball.',null,true,true);
        }else{
            $this->_losePossession();
        }
    }

    private function _shoot($action=null){
        $this->_logEvent($this->getPlayerInPossession()->getName().' takes aim and shoots...','large',true,true);
        $roll=$this->getShotDifficulty();
        if($roll<=$this->getPlayerInPossession()->getSkill() || ($action && $action!='miss')){
            $this->_shootOnGoal($action);
        }else{
            $this->_logEvent($this->getPlayerInPossession()->getName().' completely misses the Target. What a waster!',null,true,true);
            $this->_losePossession(1,false);
            $this->_logEvent('It comes for a goal kick!',null,true,true);
        }
    }

    private function _shootOnGoal($action=null){
        $roll=rand(0,100);
        if($goalkeeper = $this->getTeamInDefense()->getPlayerByPosition(1)){
            $skill = $goalkeeper->getSkill();
            if(($roll<=$skill && $action!='score') || $action=='save'){
                $this->reassignPossession($goalkeeper);
                $this->_losePossession(1,false);
                $this->_logEvent($goalkeeper->getName().' saves!',null,true,true);
            }else{
                $this->_logEvent($goalkeeper->getName().' can\'t reach the ball anymore! It hits the back of the net!',null,true,true,$this->getTeamInDefense(),$goalkeeper);
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

    private function _losePossession($toPosition=null,$commented=true){
        if($commented){
            $this->_logEvent($this->getPlayerInPossession()->getName().' loses possession.',null,true,true);
        }
        $this->_switchPossession();
        if($player = $this->getTeamInPossession()->getPlayerByPosition($toPosition)){
            $this->reassignPossession($player);
        }else{
            $this->reassignPossession($this->getTeamInPossession()->getRandomFieldPlayer());
        }
        if($commented){
            $this->_logEvent($this->getPlayerInPossession()->getName().' has got the ball now.',null,true,true);
        }
    }

    private function _coinflip(){
        $this->_flip = rand(0,1);
        $this->_possession = $this->_flip;
        $this->_switchPossession();
        $this->_logEvent($this->getTeam($this->_flip)->getName().' wins the Coin Toss!',null,true);
        $this->_kickOff();
    }

    private function _kickOff(){
        $this->_ball = array('x'=>50,'y'=>50);
        $this->_startMatch();
        $this->reassignPossession($this->getTeamInPossession()->getRandomFieldPlayer());
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
    private function _logEvent($message,$class=null,$showTeamPic=false,$showPlayerPic=false,$team=null,$player=null){
        $minuteStr = min((($this->_halftime-1)*45)+$this->_minute,$this->_halftime*45);
        if($this->_minute>45){
            $minuteStr .= ' +'.$this->_minute%45;
        }
        $event = array(
            'minute' => $minuteStr,
            'message' => $message,
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