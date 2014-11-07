<?php

//use DoneResult;

class DoneTable {
    private $doneresult;
    private $readable_id;

    public function _inizialize($readable_id, $doneresult){
        $this->readable_id = $readable_id;
        $this->doneresult = $doneresult;
        //error_log(print_r($this->doneresult->getTitleFields(), true), 0);
        //$this->doneresult = new DoneResult();
        //$this->doneresult->_inizialize($readable_id);
    }
    public function getHeaderFields($html=false, $actions = false) {
        $str = ''; //#Initializing the string to return if "html" is true;
        
        //#Obtain the list of the title's fields;
        $getTitleFields = $this->doneresult->getTitleFields();
        if($html){ //#If they are asking the html version...
            foreach ($getTitleFields as $k => $v){
                $str .= '<th>'.$v.'</th>';
            }
            if($actions){ //#If there are some actions to provide...
                foreach ($actions as $a){
                    $str .= '<th>&nbsp;</th>';
                }
            }
            return $str;
        }else{
            return $getTitleFields;
        }
    }
    public function getBodyFields($html=false, $actions = false) {
        $str = ''; //#Initializing the string to return if "html" is true;
        
        //#Obtain the list of the readable_id's fields;
        $getReadableIDFields = $this->doneresult->getReadableIDFields();

        if($html){ //#If they are asking the html version...
            foreach ($getReadableIDFields as $k => $v){
                $str .= '<td>'.$v.'</td>';
            }
            if($actions){ //#If there are some actions to provide...
                foreach ($actions as $a){
                    $str .= '<td>'.$a.'</td>';
                }
            }
            return $str;
        }else{
            return $getReadableIDFields;
        }
    }
}