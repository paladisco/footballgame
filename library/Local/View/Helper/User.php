<?php
class Local_View_Helper_User extends Zend_View_Helper_Abstract
{
    private $_size;
    private $_width;
    private $_user_id;

    public function user($size="medium"){
        $this->_size = $size;
        switch($size){
            case "small":
                $this->_width = 30;
                break;
            case "medium":
                $this->_width = 50;
                break;
            case "large":
                $this->_width = 100;
                break;
        }
        return $this;
    }

    public function renderName($user_id)
    {
        $table = new Local_Model_DbTable_User();
        try{
            $user = $table->fetchRow('id='.(int)$user_id);
            return $user['realname'];
        }catch(Exception $e){
            return "-";
        }
    }

    public function renderLabel($name,$color){
        return '<span class="label" style="background-color:'.$color.';">'.$name.'</span>';
    }

    public function renderProfilePic($user_id)
    {
        $this->_user_id = $user_id;
        return '<div class="profilepic '.$this->_size.'"><img class="img-polaroid"
        src="/image_rendered/uploaded_user/'.$this->_user_id.'/'.$this->_width.'x'.$this->_width.'_crop.jpg" /></div>';
    }


}