<?php
abstract class RF_Model_I18n extends Zend_Db_Table_Abstract
{
    public function getEntry($parent_id,$language_id){
        if($result = $this->fetchRow('language_id='.(int)$language_id.'
            AND parent_id='.(int)$parent_id)){
            return $result->toArray();
        }else{
            return array(
                'parent_id' => $parent_id,
                'language_id' => $language_id
            );
        }
    }

    public function updateTranslation($f){
        foreach($this->_unsettable as $u){
            unset($f[$u]);
        }

        if($res = $this->fetchRow('language_id='.(int)$f['language_id'].'
            AND parent_id='.(int)$f['parent_id'])){
            $data = $f;
            unset($data['language_id'],$data['parent_id']);
            $this->update($f,'language_id='.(int)$f['language_id'].'
            AND parent_id='.(int)$f['parent_id']);
        }else{
            $this->insert($f);
        }
    }
} 