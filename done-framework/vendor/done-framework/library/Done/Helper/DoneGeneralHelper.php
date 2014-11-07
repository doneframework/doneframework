<?php
class DoneGeneralHelper 
{
    protected $_count = 0;
    protected $GenericTable;

    public function getModuleName()
    {
        
        $resturn = $this->getGenericTable()->getAlbum('1');
        
        return $resturn;
        $output = "I have seen 'The Jerk' time(s).";
        return htmlspecialchars($output);
    }
    public function getGenericTable()
    {
        if (!$this->GenericTable) {
            $sm = $this->getServiceLocator();
            $this->GenericTable = $sm->get('Done\Model\GenericTable');
        }
        return $this->GenericTable;
    }
}