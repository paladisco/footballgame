<?php
class RF_Form_TableDecoratedForm extends Zend_Form
{
    protected $_elementDecorators = array(
        'ViewHelper',
        'Errors',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        array('Label', array('tag' => 'td')),
        array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
    );
	
    protected $_fileDecorators = array(
        'File',
        'Errors',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        array('Label', array('tag' => 'td')),
        array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
    );
    
    protected $_buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
        array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
    );
    
    protected $_hiddenDecorators = array('ViewHelper');

    protected $_displayGroupDecorators = array(
	    'FormElements',
	    array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
        array(array('data' => 'HtmlTag'), array('tag' => 'div'))
	);
    
    protected $_formDecorators = array(
    	'FormElements',
        array('HtmlTag', array('tag' => 'table')),
        'Form'
    );
    
    public function init()
    {
      
    }

    public function loadDefaultDecorators($exclude = array('submit','id'))
    {
       	$this->setElementDecorators($this->_elementDecorators,$exclude,false);
       	$this->setDisplayGroupDecorators($this->_displayGroupDecorators);
       	
        $this->setDecorators($this->_formDecorators);
    }
}
