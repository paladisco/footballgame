<?php
abstract class RF_Model_MultiLanguage extends RF_Model_Regular
{
    public function updateTranslation($f){
        $i18nClass = get_called_class().'I18n';
        $i18nModel = new $i18nClass;
        $i18nModel->updateTranslation($f);
    }
} 