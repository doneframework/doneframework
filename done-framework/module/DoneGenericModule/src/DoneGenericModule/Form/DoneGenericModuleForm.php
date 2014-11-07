<?php
namespace DoneGenericModule\Form;

use Zend\Form\Form;

class DoneGenericModuleForm extends Form
{
    public function __construct($whatToAdd, $name = null)
    {
        // we want to ignore the name passed
        parent::__construct('donegenericmodule');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        foreach ($whatToAdd as $wa){
            $this->add($wa);
        }
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}