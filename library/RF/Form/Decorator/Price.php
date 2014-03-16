<?php
class RF_Form_Decorator_Price extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof RF_Form_Element_Price) {
            // only want to render Date elements
            return $content;
        }

        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }

        $fr = $element->getFr();
        $rp = $element->getRp();
        $name  = $element->getFullyQualifiedName();

        $frParams = array(
            'size'      => 7,
            'maxlength' => 10,
        );
        $rpParams = array(
            'size'      => 2,
            'maxlength' => 2,
        );

        $markup = 'CHF '. $view->formText($name . '[fr]', $fr, $frParams)
                . '.' . $view->formText($name . '[rp]', $rp, $rpParams);

        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
            case self::APPEND:
            default:
                return $content . $this->getSeparator() . $markup;
        }
    }
}