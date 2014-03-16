<?php
class RF_View_Helper_FormatPrice extends Zend_View_Helper_Abstract
{
    public function formatPrice($price,$currency="CHF")
    {
    	return $currency." ".number_format($price,2);
    }
}