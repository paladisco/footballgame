<?php
class RF_Form_Decorator_Autocompleter extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof RF_Form_Element_Autocompleter) {
            // only want to render Date elements
            return $content;
        }

        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            // using view helpers, so do nothing if no view present
            return $content;
        }

        $name  = $element->getFullyQualifiedName();
		$remote = $element->getRemoteAction();
        
		
        $markup = $view->formText($name . '_inputfield', $element->getStringvalue(), array('id' => $name . '_inputfield'))
                . $view->formHidden($name, $element->getValue(), array('id' => $name));
                
        $js = '<script type="text/javascript">
        new Autocompleter.Ajax.Json($("'. $name . '_inputfield"),
	        "'.$view->url($remote).'",{
				"postVar": "acstring",
				"width": 400,
				"injectChoice": function(token){
						console.log(token);;
						var choice = new Element("li", {"html": token.marked, "id": "element_"+token.id});
						choice.inputValue = token.string;
						this.addChoiceEvents(choice).inject(this.choices);
				},
				"onSelection": function(element, selected, value, input){
					console.log(selected.id);
					$("'.$name.'").value = selected.id.substring(8);
				}
			});
		</script>';        

        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content . $js;
            case self::APPEND:
            default:
                return $content . $this->getSeparator() . $markup . $js;
        }
    }
}