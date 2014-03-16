<?php
class Game_SetupController extends Local_Controller_Action
{
    public function indexAction()
    {
        if ($this->_facebook_user_id && !$this->team) {
            try {
                $teamModel = new Game_Model_DbTable_Team();

                $user_profile = $this->_facebook_api->api('/me');
                $form = new Game_Form_Team();

                if ($this->getRequest()->isPost()) {
                    $formData = $this->getRequest()->getPost();
                    if ($form->isValid($formData)) {
                        $f = $form->getValues();
                        $teamModel->insertEntry($f,$user_profile);
                        $this->_redirect($this->view->url(array('controller'=>'index','id'=>null)));
                    } else {
                        $form->populate($formData);
                    }
                } else {
                    $form->name->setValue($user_profile['name'].' FC');
                }

                $this->view->form = $form;

            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }
        }else{
            $this->_redirect($this->view->url(array('controller'=>'team')));
        }


    }


}

