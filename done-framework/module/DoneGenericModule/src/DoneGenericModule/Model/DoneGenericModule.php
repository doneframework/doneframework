<?php
namespace DoneGenericModule\Model;

 // Add these import statements
 use Zend\InputFilter\InputFilter;
 use Zend\InputFilter\InputFilterAwareInterface;
 use Zend\InputFilter\InputFilterInterface;

 use DoneTable;
 use DoneResult;
class DoneGenericModule extends DoneTable implements InputFilterAwareInterface
{
    protected $inputFilter;                       // <-- Add this variable

    protected $doneresult;

    public function _inizialize($readable_id, $doneresult) {
        $this->doneresult = $doneresult;
    }
    public function exchangeArray($data)
    {
        $doneresult = $_SESSION['doneresult'];
        $return = array();

        $getReadableIDFields = $doneresult->getReadableIDFields();
        
        $actions = array('edit', 'delete'); //#TODO
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;

        $return['id'] = (!empty($data['id'])) ? $data['id'] : null;
        if(is_array($getReadableIDFields)){
            
            foreach ($getReadableIDFields as $k => $v){   
                $this->$v = (!empty($data[$v])) ? $data[$v] : null;
                $return[$v] = (!empty($data[$v])) ? $data[$v] : null;
            }
        }
        return $return;
    }
    public function exchangeArrayEdit($data)
    {
        $doneresult = $_SESSION['doneresult'];
        $return = array();

        $getReadableIDFields = $doneresult->getReadableIDFields();
        //
        $actions = array('edit', 'delete');
        $this->id     = (!empty($data->id)) ? $data->id : null;

        $return['id'] = (!empty($data->id)) ? $data->id : null;
        if(is_array($getReadableIDFields)){
            
            foreach ($getReadableIDFields as $k => $v){   
                $this->$v = (!empty($data->$v)) ? $data->$v : null;
                $return[$v] = (!empty($data->$v)) ? $data->$v : null;
            }
        }
        return $return;
    }
     // Add content to these methods:
     public function setInputFilter(InputFilterInterface $inputFilter)
     {
         throw new \Exception("Not used");
     }

     public function getInputFilter()
     {
         //#TODO
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