<?php
class Local_DiceRoll{
    public static function roll($sides){
        return rand(0,$sides);
    }

    public static function challenge($sides,$toBeat){
        $roll = self::roll($sides);
        if($roll<=$toBeat){
            return true;
        }else{
            return false;
        }
    }
}