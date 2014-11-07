<?php
namespace Album\Form;

use Zend\Form\Form;

class AlbumForm extends Form
{
    public function __construct($name = null, $whatToAdd)
    {

        parent::__construct('album');

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