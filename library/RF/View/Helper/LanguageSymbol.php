<?php 
class RF_View_Helper_LanguageSymbol extends Zend_View_Helper_Abstract {

	private $_language_id;
	private $_height;
    private $_class;

 	public function languageSymbol() {
		$this->_defaultValues();
		return $this;
	}

	private function _defaultValues() {
		$this->height(34);
		$this->language_id(1);
	}

    public function setClass($class){
        $this->_class = $class;
        return $this;
    }

	public function language_id($language_id) {
  		$this->_language_id = $language_id;
  		return $this;
 	}
 	
	public function height($height) {
  		$this->_height = $height;
  		return $this;
 	}

	public function render() {
		$code = '<img src="'.$this->view->url(array('module'=>'default','controller'=>'image','action'=>'index',
            'h'=>$this->_height,'path'=>'i18n','filename'=>$this->_language_id.'.png','crop'=>'ratio'),NULL,
            true).'"';
        if($this->_class){
            $code .= ' class="'.$this->_class.'" ';
        }
        $code .='/>';
        return $code;
	}

}