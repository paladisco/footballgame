<?php
class Local_Automailer extends Zend_Mail{

    public function __construct($charset='UTF-8'){
        parent::__construct($charset);
    }

    private function _sendTextMail($recipient,$subject,$body,$bcc=null){
        $this->addTo($recipient);
        if($bcc){
            $this->addBcc($bcc);
        }
        $this->setSubject($subject);
        $this->setBodyText($body."\n\n".MAIL_FOOTER);
        $this->setFrom(MAIL_SENDER_ADDRESS,MAIL_SENDER_NAME);
        try {
            $this->send();
            return true;
        } catch (Zend_Mail_Transport_Exception $e) {
            echo "Versand an ".$recipient." fehlgeschlagen! (Message: ".$e->getMessage().")";
        } catch (Zend_Mail_Protocol_Exception $e) {
            echo "Versand an ".$recipient." fehlgeschlagen! (Message: ".$e->getMessage(). ")";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}