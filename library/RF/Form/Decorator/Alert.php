<?php
class RF_Form_Decorator_Alert extends Zend_Form_Decorator_Abstract
{
	public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $errors = $element->getMessages();
        if (empty($errors)) {
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $errorHelper = new Zend_View_Helper_FormErrors();
        $errors = $errorHelper
        	->setView($view)
        	->setElementEnd('</p>')
        	->setElementStart('<div%s><p>')
        	->setElementSeparator('</p><p>')
        	->formErrors($errors, $this->getOptions());

        switch ($placement) {
            case self::APPEND:
                return $content . $separator . $errors;
            case self::PREPEND:
                return $errors . $separator . $content;
        }
    }
}