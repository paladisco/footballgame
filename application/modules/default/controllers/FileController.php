<?php
class FileController extends Zend_Controller_Action 
{    
	protected $_table = null;
	
    public function init(){
    	parent::init();
    	$this->_table = new Local_Model_DbTable_Downloads();
    }
	
    public function getAction()
    {
    	$download_id = $this->getRequest()->getParam('id');
    	$d = $this->_table->getEntry($download_id);

    	$this->_helper->viewRenderer->setNoRender(true);
	    $this->_helper->layout->disableLayout();

        /** @noinspection PhpDuplicateArrayKeysInspection */
        $ext = array("pdf" => "application/pdf",
	    			"eps" => "application/postscript",
					"exe" => "application/octet-stream",
					"zip" => "application/zip",
					"xls" => "application/vnd.ms-excel",
					"ppt" => "application/vnd.ms-powerpoint",
                    "doc" => "application/msword",
                    "docx"=> "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
					"gif" => "image/gif",
					"png" => "image/png",
					"jpg" => "image/jpg",
					"mp3" => "audio/mpeg",
					"mp3" => "audio/mp3",
					"wav" => "audio/x-wav",
					"mpe" => "video/mpeg",
					"mov" => "video/quicktime",
					"avi" => "video/x-msvideo");
	    
	    header('Content-type: '.$ext[$d['ext']]);
		header('Content-Disposition: attachment; filename="'.$d['filename'].'"');
		readfile(DOCUMENT_ROOT.'/files/downloads/'.$d[id]);
    	
    }
}