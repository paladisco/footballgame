<?php 
class RF_Model_Timeable extends RF_Model_Regular
{
    private $_lastMonthDays;

    public function init(){
        $m = date('m');
        $y = date('Y');
        $lastmonth = $m-1;
        if($lastmonth<1){
            $lastmonth=12;
        }
        $this->_lastMonthDays = cal_days_in_month(CAL_GREGORIAN, $lastmonth, $y);
    }

    public function setTimeframe($datefrom,$dateto){
        $this->_timeframe = array(
            'start' => strtotime($datefrom),
            'end' => strtotime($dateto)
        );
        return $this;
    }

    public function setAllTimeframe(){
        $this->_timeframe = array(
            'start' => 0,
            'end' => time()
        );
        return $this;
    }

    public function setPastMonthsTimeframe($months=6){
        $y = date('Y');
        $m = date('m');
        $this->_timeframe = array(
            'start' => mktime(0,0,0,$m-$months+1,1,$y),
            'end' => time()
        );
        return $this;
    }

    public function setPastMonthTimeframe(){
        $y = date('Y');
        $m = date('m');
        $d = date('d');
        $this->_timeframe = array(
            'start' => mktime(0,0,0,$m,$d-$this->_lastMonthDays,$y),
            'end' => time()
        );
        return $this;
    }

    public function setCurrentMonthTimeframe(){
        $y = date('Y');
        $m = date('m');
        $this->_timeframe = array(
            'start' => mktime(0,0,0,$m,1,$y),
            'end' => time()
        );
        return $this;
    }

    public function setPastWeekTimeframe(){
        $y = date('Y');
        $m = date('m');
        $d = date('d');
        $this->_timeframe = array(
            'start' => mktime(0,0,0,$m,$d-7,$y),
            'end' => time()
        );
        return $this;
    }

    public function getMonths($fullname=false){
        $month = date('n',$this->_timeframe['start']);
        $year = date('Y',$this->_timeframe['start']);
        $y=date('Y');
        $m=date('n');
        while(($month<=$m && $year==$y) || $year!=$y){
            if($month>12){
                $month=1;$year++;
            }
            if($fullname){
                $monthLabel = strftime('%B',mktime(0,0,0,$month,1,$year)).' '.$year;
            }else{
                $monthLabel = (int)$month.'/'.$year;
            }
            $arr[$year.'-'.(int)$month] = $monthLabel;
            $month++;
        }
        return $arr;
    }

    public function getDays(){
        $day = date('d',$this->_timeframe['start']);
        $month = date('m',$this->_timeframe['start']);
        $days = floor(($this->_timeframe['end']-$this->_timeframe['start'])/24/60/60);
        for($i=0;$i<=$days;$i++){
            if($day>$this->_lastMonthDays && date('m')!=$month){
                $day=1;$month++;
            }
            if($month>12){
                $month=1;
            }
            $arr[$day.'-'.(int)$month] = (int)$day.'/'.$month;
            $day++;
        }
        return $arr;
    }

    public function setSelectedMonthYear($m,$y){
        $this->_timeframe = array(
            'start' => mktime(0,0,0,$m,1,$y),
            'end' => mktime(0,0,0,$m+1,1,$y)
        );
        return $this;
    }

    public function setPastYearTimeframe(){
        $y = date('Y');
        $m = date('m');
        $this->_timeframe = array(
            'start' => mktime(0,0,0,$m+1,1,$y-1),
            'end' => time()
        );
        return $this;
    }

} 