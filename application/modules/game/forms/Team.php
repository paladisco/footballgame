<?php
class Game_Form_Team extends RF_Form_BootstrapForm
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName('user');

        $elements[] = new Zend_Form_Element_Hidden('id');

        $elements[] = new Zend_Form_Element_Text('name');
        end($elements)->setLabel('Vollständiger Name')
            ->setRequired(true)
            ->addFilter('StringTrim');

        $elements[] = new Zend_Form_Element_Text('email');
        end($elements)->setLabel('E-Mail')
            ->setRequired(false)
            ->addFilter('StringTrim');

        $elements[] = new Zend_Form_Element_Submit('submit');
        end($elements)->setLabel('erstellen');

        $this->addElements($elements);
        $this->setAttrib('horizontal',true);
    }
}
