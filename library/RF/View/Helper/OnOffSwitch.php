<?php
class RF_View_Helper_OnOffSwitch extends Zend_View_Helper_Abstract
{
    public function onOffSwitch($field,$id,$value,$icon='check',$function='switchFlag')
    {
        ob_start();
        if ($value==1){ ?>
            <a class="btn btn-success" onclick="<?php echo $function; ?>(<?php echo $id; ?>,'<?php echo $field; ?>',<?php echo $value; ?>);">
                <i class="icon-<?php echo $icon; ?>"> </i>
            </a>
            <?php } else { ?>
            <a class="btn btn-danger" onclick="<?php echo $function; ?>(<?php echo $id; ?>,'<?php echo $field; ?>',<?php echo $value; ?>);">
                <i class="icon-<?php echo $icon; ?>"> </i>
            </a>
        <?php }
        return ob_get_clean();
    }
}