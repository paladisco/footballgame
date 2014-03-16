<?php
final class Local_Model_Identity {

    private static $instance = NULL;
	
	private $_identity;
	
	private function __construct() {
		$this->_identity = NULL;
	}
	
    /**
     * @static
     * @return Local_Model_Identity
     */
    public static function getInstance() {

		if (NULL === self::$instance) {
			self::$instance = new self ();
		}
		return self::$instance;
	}

	private function __clone() {
	}
	
	public function setIdentity($identity){
		$this->_identity = $identity;
	}
	
	public function destroyIdentity(){
		$this->_identity = NULL;
	}
	
	public function hasIdentity(){
		return $this->_identity?true:false;
	}
	
	public function getUsername(){
		return $this->_identity->username;
	}

    public function getID(){
        return $this->_identity->id;
    }

    public function getActive(){
        return $this->_identity->active;
    }

    public function getTeamID(){
        return $this->_identity->team_id;
    }

    public function getPriceID(){
        return $this->_identity->price_id;
    }

    public function getRoleID(){
        return $this->_identity->role_id;
    }

    public function getEmail(){
		return $this->_identity->email;
	}
	
	public function getRealname(){
		return $this->_identity->realname;
	}
	
	public function getImage($width){
        return "/image_rendered/uploaded_team/".$this->_identity->team_id."/".$width."x".$width."_crop.jpg";
    }

	public function isSuperAdmin(){
		return $this->_identity->permission==1?true:false;
	}
	public function isBookkeeper(){
		return $this->_identity->permission==2?true:false;
	}
	public function isWorker(){
		return $this->_identity->permission==3?true:false;
	}
	public function isGuest(){
		return $this->_identity->permission==4?true:false;
	}

    public function checkRights($rolestring){
		$rights = split(',',$rolestring);
		if(is_array($rights)){
			foreach($rights as $r){
				if($this->_identity->role_id==$r){
					return true;
				}
			}
			return false;
		}elseif($this->_identity->role_id==$rights){
			return true;
		}else{
			return false;
		}
	}

    public function authenticate($auth,$f){
        $username = $f['username'];
        $password = $f['password'];
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('users')
            ->setIdentityColumn('username')
            ->setCredentialColumn('password')
            ->setCredentialTreatment('MD5(?)');

        // pass to the adapter the submitted username and password
        $authAdapter->setIdentity($username)->setCredential($password);

        //$select = $authAdapter->getDbSelect();
        //$select->group('email');

        $result = $auth->authenticate($authAdapter);

        // is the user a valid one?
        if($result->isValid())
        {
            // get all info about this user from the login table
            // ommit only the password, we don't need that
            $userInfo = $authAdapter->getResultRowObject(null, 'password');

            // the default storage is a session with namespace Zend_Auth
            $authStorage = $auth->getStorage();
            $authStorage->write($userInfo);

            return true;
        }else{
            $messages = $result->getMessages();
            throw new Zend_Exception($messages[0]);
        }
    }
}