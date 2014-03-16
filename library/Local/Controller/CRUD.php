<?php
class Local_Controller_CRUD extends Local_Controller_Backend
{
    protected $_form;
    protected $_model;

    protected $_title;
    protected $_viewpath;
    protected $_label;

    protected $_titleField = 'name';

    public function init(){
        $this->_viewpath = strtolower($this->getRequest()->getControllerName());
        parent::init();
    }

    public function addAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);

        $this->view->title = "New ".$this->_label;

        $form = $this->_form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $f = $form->getValues();

                $this->_model->insertEntry($f);
                $this->_redirect($this->view->url(array('action'=>'index')));

            } else {
                $form->populate($formData);
            }
        }

        echo $this->view->partial($this->_viewpath.'/_form.phtml',array('form'=>$form));
    }

    public function editAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);

        $form = $this->_form;
        $id = $this->getRequest()->getParam('id');
        $entry = $this->_model->getEntry($id);

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $f = $form->getValues();
                $this->_model->updateEntry($f,$id);
                $this->_redirect($this->view->url(array('action'=>'index','id'=>null)));

            } else {
                $form->populate($formData);
            }
        } else {
            if($id){
                $form->populate($entry);
            }
        }
        $this->view->title = 'Edit "'.$entry[$this->_titleField].'"';

        echo $this->view->partial($this->_viewpath.'/_form.phtml',array('form'=>$form));
    }

    public function deleteAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->title = $this->_label." löschen";
        $this->view->headTitle($this->view->title, 'APPEND');

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Ja') {
                $id = $this->getRequest()->getPost('id');
                $this->_model->deleteEntry($id);
            }
            $this->_redirect($this->view->url(array('action'=>'index','id'=>null)));

        } else {
            $id = $this->_getParam('id', 0);
            $this->view->entry = $this->_model->getEntry($id);
            echo $this->view->render('_delete.phtml');
        }
    }

    function visibilityAction()
    {
        $this->_helper->layout->disableLayout();

        $id = $this->_getParam('id', 0);
        $active = $this->_model->toggleVisibility($id);

        if($active==0){
            $icon = "cross";
        }else{
            $icon = "check";
        }
        $this->view->icon = $icon;
    }

    function orderAction()
    {
        $elements = $this->_getParam('elements', 0);
        $e = explode(",",$elements);

        foreach($e as $pos => $id){

            $parsed_id = substr(strstr($id,"_"),1);
            if($parsed_id!=""){
                $this->_model->updatePos($parsed_id,$pos+1);
            }

        }
        $this->loadAction();
    }
}

