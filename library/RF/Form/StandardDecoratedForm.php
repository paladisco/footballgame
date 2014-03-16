<?php
class RF_Form_StandardDecoratedForm extends Zend_Form
{
    protected $_elementDecorators = array(
        'ViewHelper',
       	array('Alert',array('tag' => 'div', 'class' => 'alert-message')),
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'controls')),
        array('Label',array('tag' => 'label', 'class' => 'control-label')),
        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'clearfix control-group'))
    );

    protected $_fileDecorators = array(
        'File',
        array('Alert',array('tag' => 'div', 'class' => 'alert-message')),
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'controls')),
        array('Label',array('tag' => 'label', 'class' => 'control-label')),
        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'clearfix control-group'))
    );

    protected $_captchaDecorators = array(
        'Captcha',
        array('Alert',array('tag' => 'div', 'class' => 'alert-message')),
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'controls')),
        array('Label',array('tag' => 'label', 'class' => 'control-label')),
        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'clearfix control-group'))
    );

    protected $_buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'controls')),
        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'clearfix control-group'))
    );
    
    protected $_hiddenDecorators = array('ViewHelper');

    protected $_displayGroupDecorators = array(
	    'FormElements',
    	'Fieldset'
	);
    
    protected $_formDecorators = array(
    	'FormElements',
        'Form',
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'clearfix formwrapper insetElement form-horizontal')),
    );
    
    public function init()
    {
      	$this->addPrefixPath('RF_Form_Decorator', 'RF/Form/Decorator/', 'decorator');
    }

    public function loadDefaultDecorators($exclude = array('submit','id','image'))
    {
       	$this->setElementDecorators($this->_elementDecorators,$exclude,false);
       	
       	if($this->id)
       		$this->id->setDecorators($this->_hiddenDecorators); 
       	if($this->submit)
       		$this->submit->setDecorators($this->_buttonDecorators);
       	if($this->image)
       		$this->image->setDecorators($this->_fileDecorators);
       	
       	$this->setDisplayGroupDecorators($this->_displayGroupDecorators);
       	
        $this->setDecorators($this->_formDecorators);
    }
}
