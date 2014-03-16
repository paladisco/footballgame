<?php
class RF_Form_Element_Price extends Zend_Form_Element_Xhtml
{
    protected $_fr;
    protected $_rp;
    
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
            $this->addDecorator('Price')
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
    
    public function setFr($value)
    {
        $this->_fr = (int) $value;
        return $this;
    }

    public function getFr()
    {
        return $this->_fr;
    }

    public function setRp($value)
    {
        $this->_rp = (int) $value;
        return $this;
    }

    public function getRp()
    {
        return $this->_rp;
    }

    public function setValue($value)
    {
    	if(!is_array($value)){
	    	$this->setFr(round($value))
	             ->setRp(($value*100)%100);
        }else{
        	$this->setFr($value['fr'])
        		->setRp($value['rp']);
        }
        return $this;
    }

    public function getValue()
    {
        return $this->getFr()+$this->getRp()/100;
    }
}