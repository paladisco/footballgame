<?php 
class RF_View_Helper_ConfirmDeletion extends Zend_View_Helper_Abstract {

	private $_label;
	private $_itemname;
	private $_id;
	private $_hiddenvalues = array();
	
 	public function confirmDeletion($id) {
		$this->_defaultValues();
		$this->_hiddenvalues['id'] = $id;
		return $this;
	}

	private function _defaultValues() {
		$this->label("Möchten Sie den Eintrag ###ITEMNAME### wirklich löschen?");
		$this->itemname("");
	}

	public function label($label) {
  		$this->_label = $label;
  		return $this;
 	}
 	
 	public function addHiddenValues($vals) {
 		foreach($vals as $i => $v){
			$this->_hiddenvalues[$i] = $v;
		}
  		return $this;
 	}
 	
	public function itemname($itemname) {
  		$this->_itemname = $itemname;
  		return $this;
 	}

	public function render() {
		$ret = '<p>'.str_replace("###ITEMNAME###",$this->_itemname,$this->_label).'</p>';
		$ret .= '<form action="'.$this->view->url(array('action'=>'delete')).'" method="post">';
		foreach($this->_hiddenvalues as $i => $v){
			$ret .= '<input type="hidden" name="'.$i.'" value="'.$v.'" />';
		}
  		$ret .= '<input class="btn" type="submit" name="del" value="Ja" />
  		<input class="btn" type="submit" name="del" value="Nein" />';
  		return $ret;
	}

}