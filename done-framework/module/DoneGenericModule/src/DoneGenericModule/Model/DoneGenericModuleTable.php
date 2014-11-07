<?php
namespace DoneGenericModule\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Insert;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;


class DoneGenericModuleTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($tableName, $doneresult_results, $paginated=false)
    {
        if ($paginated) {
            // create a new Select object for the table donegenericmodule
            $select = new Select($tableName);//#REPLACE-TABLE
            // create a new result set based on the DoneGenericModule entity
            $resultSetPrototype = new ResultSet();
            $donegenericmodule = new DoneGenericModule();
            $donegenericmodule->_inizialize('dd', $doneresult_results);
            
            $resultSetPrototype->setArrayObjectPrototype($donegenericmodule);
            // create a new pagination adapter object
            $paginatorAdapter = new DbSelect(
                // our configured select object
                $select,
                // the adapter to run it against
                $this->tableGateway->getAdapter(),
                // the result set to hydrate
                $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getDoneGenericModule($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveDoneGenericModule($tablename, $data)
    {

        $id = (int) $data['id'];
        if ($id == 0) {

            $this->tableGateway = new TableGateway($tablename, $this->tableGateway->getAdapter());//#REPLACE-TABLE

            $this->tableGateway->insert($data);
        } else {
            if ($this->getDoneGenericModule($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('DoneGenericModule id does not exist');
            }
        }
    }

    public function saveDoneGenericModuleEdit(DoneGenericModule $donegenericmodule, $data, $tablename)
    {

        $this->tableGateway = new TableGateway($tablename, $this->tableGateway->getAdapter());//#REPLACE-TABLE

        $id = (int) $donegenericmodule->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getDoneGenericModule($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('DoneGenericModule id does not exist');
            }
        }
    }

    public function deleteDoneGenericModule($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}