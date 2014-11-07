<?php
//namespace Album\Model;

 // Add these import statements
 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;

 use DoneTable;
class Generic extends DoneTable implements InputFilterAwareInterface
{
    public $id;
    public $artist;
    public $title;
    protected $inputFilter;                       // <-- Add this variable


    public function exchangeArray($data)
    {
        $this->_inizialize('albums');
        $getReadableIDFields = $this->getBodyFields($html = false, $actions = array('Edit', 'Delete'));
        $actions = array('edit', 'delete');
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        
        foreach ($getReadableIDFields as $k => $v){
            $this->$v = (!empty($data[$v])) ? $data[$v] : null;
        }   
        /*$this->title  = (!empty($data['title'])) ? $data['title'] : null;
        $this->test  = (!empty($data['test'])) ? $data['test'] : null;
        $this->newcolumn  = (!empty($data['newcolumn'])) ? $data['newcolumn'] : null;
         * 
         */
    }
     // Add content to these methods:
     public function setInputFilter(InputFilterInterface $inputFilter)
     {
         throw new \Exception("Not used");
     }

     public function getInputFilter()
     {
         if (!$this->inputFilter) {
             $inputFilter = new InputFilter();

             $inputFilter->add(array(
                 'name'     => 'id',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'Int'),
                 ),
             ));

             $inputFilter->add(array(
                 'name'     => 'artist',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 100,
                         ),
                     ),
                 ),
             ));

             $inputFilter->add(array(
                 'name'     => 'title',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 100,
                         ),
                     ),
                 ),
             ));

             $this->inputFilter = $inputFilter;
         }

         return $this->inputFilter;
     }
     public function getArrayCopy()
     {
         return get_object_vars($this);
     }
 }