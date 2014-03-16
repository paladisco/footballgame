<?php
class RF_Form_Decorator_Date extends Zend_Form_Decorator_Abstract {
	
	public function render($content) {

		$element = $this->getElement ();
		if (! $element instanceof RF_Form_Element_Date) {
			// only want to render Date elements
			return $content;
		}
		
		$view = $element->getView ();
		if (! $view instanceof Zend_View_Interface) {
			// using view helpers, so do nothing if no view present
			return $content;
		}
		
		$day = $element->getDay ();
		$month = $element->getMonth ();
		$year = $element->getYear ();
		$name = $element->getFullyQualifiedName ();
		
		//$params = array ('size' => 2, 'maxlength' => 2 );
		//$yearParams = array ('size' => 4, 'maxlength' => 4 );
		
		$dayOptions = array();
		for($d=1;$d<32;$d++){
			$dayOptions[$d] = $d;
		}
		$monthOptions = array();
		for($m=1;$m<13;$m++){
			$monthOptions[$m] = $m;
		}
		$yearOptions = array();
		for($y=1900;$y<date('Y');$y++){
			$yearOptions[$y] = $y;
		}
		
		//$markup = $view->formText($name . '[day]', $day, $params) . ' / ' . $view->formText($name . '[month]', $month, $params) . ' / ' . $view->formText($name . '[year]', $year, $yearParams);
 		
        $markup = $view->formSelect ( $name . '[day]', $day, $params, $dayOptions ) . ' / ' . $view->formSelect ( $name . '[month]', $month, $params, $monthOptions ) . ' / ' . $view->formSelect ( $name . '[year]', $year, $yearParams, $yearOptions );
		
		switch ($this->getPlacement ()) {
			case self::PREPEND :
				return $markup . $this->getSeparator () . $content;
			case self::APPEND :
			default :
				return $content . $this->getSeparator () . $markup;
		}
	}
}