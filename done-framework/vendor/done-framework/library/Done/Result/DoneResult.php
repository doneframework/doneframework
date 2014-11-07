<?php

class DoneResult {
    private $readable_id;
    private $readableIDFields;
    private $titleFields;
    
    public function _inizialize($readable_id){
        $this->readable_id = $readable_id;
    }

    public function getTitleFields() {
        return $this->titleFields;
    }
    public function setTitleFields($titleFields) {
        $this->titleFields = $titleFields;
    }
    
    public function getReadableIDFields() {
        //error_log(print_r($this->readableIDFields, true), 0);
        //return array('title', 'artistartist', 'test', 'newcolumn');
        return $this->readableIDFields;
    }
    public function setReadableIDFields($readableIDFields) {
        $this->readableIDFields = $readableIDFields;
    }
}
