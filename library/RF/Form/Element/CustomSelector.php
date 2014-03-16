<?php
class RF_Form_Element_CustomSelector extends Zend_Form_Element_Select
{
    private $_options;

    public function setOptions($model,$order='pos',$value='id',$content='name',$where="1",$required=true)
    {
        $options = $model->fetchAll($where,$order);
        foreach($options as $o){
            $this->addMultiOption($o[$value],$o[$content]);
        }
        $this->setRequired($required);
        $this->_options = $options;
        return $this;
    }

    public function setComplexOptions($result,$value='id',$content='name',$required=true)
    {
        foreach($result as $o){
            $this->addMultiOption($o[$value],$o[$content]);
        }
        $this->setRequired($required);
        $this->_options = $result;
        return $this;
    }

    public function allowEmptyOption($value=0,$label = "please select..."){
    	$options = $this->getMultiOptions();
    	$this->setMultiOptions(array($value=>$label));
    	$this->addMultiOptions($options);
        return $this;
    }

    public function getRawResult(){
        return $this->_options->toArray();
    }
 
}
