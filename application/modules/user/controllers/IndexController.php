<?php
class User_IndexController extends Local_Controller_Backend
{
    /**
     * @var $_model Local_Model_User
     */

    protected $_model = null;
	protected $_form = null;
	protected $_label = "Benutzer";
	protected $_viewpath = "user";

    public function init(){
    	parent::init();
    	$this->_model = new Local_Model_User();
	 	$this->_form = new User_Form_User();
   		$this->view->viewpath = $this->_viewpath;
    }
	
	public function indexAction()
    {
		$this->view->title = $this->_label;
        $active = $this->getRequest()->getParam('active',1);
        $this->view->headTitle($this->view->title, 'APPEND');
        $this->view->result = $this->_model->retrieveAll($active);
        $this->view->active = $active;
    }
    
	function addAction() 
    { 
        $this->view->title = $this->_label." hinzufügen"; 
        $this->view->headTitle($this->view->title, 'APPEND'); 

        $form = $this->_form; 
        $form->submit->setLabel('erstellen'); 
        $this->view->form = $form; 
        
        if ($this->getRequest()->isPost()) { 
            $formData = $this->getRequest()->getPost(); 
            if ($form->isValid($formData)) { 
                $f = $form->getValues(); 
                
                $new_id = $this->_model->addInstance($f);
                
                $this->_redirect($this->view->url(array('action'=>'index')));
                
            } else { 
                $form->populate($formData); 
            } 
        }
    }

    function editAction() 
    { 
        $this->view->title = $this->_label." bearbeiten"; 
        $this->view->headTitle($this->view->title, 'APPEND'); 
        
 		$form = $this->_form;
 		$form->removeElement('password');
        $form->submit->setLabel('aktualisieren'); 
        $this->view->form = $form; 
                
        if ($this->getRequest()->isPost()) { 
            $formData = $this->getRequest()->getPost(); 
            if ($form->isValid($formData)) { 
                $f = $form->getValues();
                
                $this->_model->updateInstance($f);
                $this->_redirect($this->view->url(array('action'=>'index')));
                
            } else { 
                $form->populate($formData); 
            } 
        } else { 
            $id = $this->_getParam('id', 0); 
            if ($id > 0) { 
                $form->populate($this->_model->retrieveOne($id)->toArray());
            } 
        } 
    }

    function activateAction(){
        $id = $this->_getParam('id', 0);
        $userModel = new Local_Model_DbTable_User();
        $user = $userModel->getEntry($id);
        if($user->active==1){
            $userModel->update(array('active'=>0),'id='.(int)$id);
        }else{
            $userModel->update(array('active'=>1),'id='.(int)$id);
        }
        $this->_redirect($this->view->url(array('action'=>'index')));
    }
}
