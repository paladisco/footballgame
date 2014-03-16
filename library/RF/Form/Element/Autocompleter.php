<?php
class RF_Form_Element_Autocompleter extends Zend_Form_Element_Xhtml
{
	protected $_controller;
	protected $_action;
	protected $_model;
	protected $_field;
	protected $_stringvalue;
	protected $_value;
	
	public function  __construct($spec, $options = null)
    {
        $this->addPrefixPath(
            'RF_Form_Decorator',
            'RF/Form/Decorator',
            'decorator'
        );
        parent::__construct($spec, $options);
    }
    
	public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('Autocompleter')
                 ->addDecorator('Errors')
                 ->addDecorator('Description', array(
                    'tag' => 'p',
                    'class' => 'description')
                 )
                 ->addDecorator('HtmlTag', array(
                    'tag' => 'dd',
                    'id'  => $this->getName() . '-element')
                 )
                 ->addDecorator('Label', array('tag' => 'dt'));
        }
    }
    
    public function setRemoteAction($controller,$action)
    {
        $this->_controller = $controller;
        $this->_action = $action;
        return $this;
    }
    
    public function getRemoteAction()
    {
    	return array('controller'=>$this->_controller,
    		'action'=>$this->_action);	
    }
    
    public function setModel($model)
    {
        $this->_model = $model;
        return $this;
    }
    
    public function getModel()
    {
    	return $this->_model;	
    }
    
	 public function setField($field)
    {
        $this->_field = $field;
        return $this;
    }
    
    public function getField()
    {
    	return $this->_field;	
    }
    
    public function setStringvalue($value){
    	$this->_stringvalue = $value;
    	return $this;
    }
    public function getStringvalue(){
    	return $this->_stringvalue;
    }
    
	public function setValue($value)
    {
        if (is_int((int)$value)) {
        	$field = $this->_field;
        	$r = $this->_model->retrieveOne($value);
            $this->setStringvalue($r->$field);
			$this->_value = $value;
        }else{
            throw new Exception('Invalid Field value provided');
        }

        return $this;
    }

    public function getValue(){
    	return $this->_value;
    }
}