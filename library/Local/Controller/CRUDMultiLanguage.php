<?php
class Local_Controller_CRUDMultiLanguage extends Local_Controller_CRUD
{
    public function init(){
        parent::init();
        $languageModel = new Local_Model_DbTable_Language();
        $this->view->languages = $languageModel->fetchAll();
    }

    public function translateAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);

        $language_id = $this->_getParam('language_id');
        $parent_id = $this->_getParam('parent_id');

        $languageModel = new Local_Model_DbTable_Language();
        $l = $languageModel->fetchRow('id='.(int)$language_id);

        $parent = $this->_model->getEntry($parent_id);
        $this->view->title = $this->view->languageSymbol()->language_id($language_id)->render().' '.$parent['name'].' ('.$l['name'].')';

        $entry = $this->_i18nModel->getEntry($parent_id,$language_id);

        $form = $this->_i18nForm;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $f = $form->getValues();

                $this->_model->updateTranslation($f);
                $this->_redirect($this->view->url(array('action'=>'index')));

            } else {
                $form->populate($formData);
            }
        }else{
            $form->populate($entry);
        }

        echo $this->view->partial($this->_viewpath.'/_form.phtml',array('form'=>$form));
    }
}

