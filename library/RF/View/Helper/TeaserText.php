<?php
class RF_View_Helper_TeaserText extends Zend_View_Helper_Abstract
{
    public function teaserText($content,$length=160)
    {
		return substr($content,0,160).(strlen($content)>$length?'...':'');
    }
}