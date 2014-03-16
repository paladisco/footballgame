<?php 
class RF_Model_Moveable extends RF_Model_Regular 
{ 
	public function updatePos($id, $pos, $where="")
    { 
        $data = array( 
            'pos' => $pos,
        ); 
    	return parent::update($data, 'id = ' . (int)$id . $where);
    } 
    
    protected function newPosition($where=1){
    	$pos = $this->getAdapter()->fetchOne("SELECT MAX(pos) FROM $this->_name WHERE $where")+1;
   		return $pos;
    }
    
} 