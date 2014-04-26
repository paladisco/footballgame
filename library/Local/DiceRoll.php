<?php
class Local_DiceRoll{
    public static function roll($sides){
        return rand(0,$sides);
    }

    public static function log($message){
        if($logWriter = Zend_Registry::get('logger')){
            $logWriter->info($message);
        }
    }

    public static function challenge($sides,$toBeat){
        $roll = self::roll($sides);
        if($logWriter = Zend_Registry::get('logger')){
            $logWriter->info('Rolling '.$sides.' sided dice, rolling '.$roll.', to beat '.$toBeat);
        }

        if($roll<=$toBeat){
            return true;
        }else{
            return false;
        }
    }
}