<?php
class RF_Export_CSVWriter{

    private $_fieldSeparator;
    private $_lineSeparator;
    private $_fields;
    private $_data;

    public function __construct($lineseparator = "\n", $fieldseparator = ','){

        $this->_fieldSeparator = $fieldseparator;
        $this->_lineSeparator = $lineseparator;

    }

    public function initializeData($fields,$data){

        $this->_fields = $fields;
        $this->_data = $data;

    }

    public function renderOutput(){

        $data = '';
        $line = '';

        $field = array();
        foreach($this->_fields as $i => $f){
            $f=str_replace('"', '""', $f);
            $field[] = '"'.str_replace('"', '""', $f).'"';
        }
        $line = join($this->_fieldSeparator,$field);
        $data.=trim($line).$this->_lineSeparator;

        foreach($this->_data as $row) {

            $field = array();
            foreach($row as $value){
                $value=str_replace('"', '""', $value);
                $field[] = '"'.str_replace('"', '""', $value).'"';
            }
            $line = join($this->_fieldSeparator,$field);

            $data.=trim($line).$this->_lineSeparator;
        }

        $data = str_replace("\r","",$data);

        return $data;

    }

}