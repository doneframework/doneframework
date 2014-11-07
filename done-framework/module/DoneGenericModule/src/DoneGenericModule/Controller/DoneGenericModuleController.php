<?php

namespace DoneGenericModule\Controller;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoneGenericModule\Model\DoneGenericModule;          // <-- Add this import
use DoneGenericModule\Form\DoneGenericModuleForm;       // <-- Add this import
use DoneResult;
use DoneTable;
use DoneForm;

class DoneGenericModuleController extends AbstractActionController
{
    protected $donegenericmoduleTable;
    protected $fieldTable;
    protected $doneresult;

    public function indexAction()
    {
        
        $readable_id = 'albums'; //#This is the id associated to this action and table
        $form_or_result = 'result';
        $permission_in_this_method = $this->zfcUserAuthentication()->getIdentity()->getPermission();
        $this->fieldTable_results = $permission_in_this_method['results']; //#RESULTS: Select the table's fields

        $getFields_results = $this->getFields($form_or_result, $this->fieldTable_results, $readable_id); //#Catch the field based on result_readable_id
        $readableIDFields_results = $titleFields_results = $doneresult_results = $getFields_results['doneresult'];

        $_SESSION['doneresult'] = $doneresult_results;
        
        $tablename = $getFields_results['table_readable_id_DB'][0];
        
        // grab the paginator from the DoneGenericModuleTable
        $paginator = $this->getDoneGenericModuleTable()->fetchAll($tablename, $doneresult_results, true);//#REPLACE-TABLE
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage(10);

        $donetable = new DoneTable();
        $donetable->_inizialize($readable_id, $doneresult_results);

        return new ViewModel(array(
            'paginator' => $paginator,
            'donetable' => $donetable,
            'titleFields' => $titleFields_results,
            'readableIDFields' => $readableIDFields_results
        ));
    }
    // Add content to this method:
    public function addAction()
    {
        $readable_id = 'add-album';
        $form_or_result = 'form';
        
        $permission_in_this_method = $this->zfcUserAuthentication()->getIdentity()->getPermission();
        $this->fieldTable_forms = $permission_in_this_method['forms']; //#FORMS: Select the form's fields
        
        $getFields_forms = $this->getFields($form_or_result, $this->fieldTable_forms, $readable_id);
        $doneresult_forms = $getFields_forms['doneresult'];
        
        $tablename = $getFields_forms['table_readable_id_DB'][0];
        
        $_SESSION['doneresult'] = $doneresult_forms;
        
        $doneform = new DoneForm();
        $doneform->_inizialize('donegenericmodules', $_SESSION['doneresult']);
        
        $form = new DoneGenericModuleForm($doneform->getWhatToAdd(), $name = null);

        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $donegenericmodule = new DoneGenericModule();
            $donegenericmodule->_inizialize($readable_id, $doneresult_forms);
            
            $form->setInputFilter($donegenericmodule->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $return = $donegenericmodule->exchangeArray($form->getData());
                $this->getDoneGenericModuleTable()->saveDoneGenericModule($tablename, $return);

                // Redirect to list of donegenericmodules
                return $this->redirect()->toRoute('zfcadmin');
            }
        }
        //
        return new ViewModel(array(
            'doneform' => $doneform,
            'form' => $form
        ));
        //return array('form' => $form);
    }

    // Add content to this method:
    public function editAction()
    {
        $readable_id = 'add-album';
        $form_or_result = 'form';
        
        $permission_in_this_method = $this->zfcUserAuthentication()->getIdentity()->getPermission();
        $this->fieldTable_forms = $permission_in_this_method['forms']; //#FORMS: Select the form's fields
        
        $getFields_forms = $this->getFields($form_or_result, $this->fieldTable_forms, $readable_id);
        $doneresult_forms = $getFields_forms['doneresult'];
        
        $tablename = $getFields_forms['table_readable_id_DB'][0];
        
        $_SESSION['doneresult'] = $doneresult_forms;
        
        $doneform = new DoneForm();
        $doneform->_inizialize('donegenericmodules', $_SESSION['doneresult']);
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('zfcadmin', array(
                'action' => 'add'
            ));
        }

        try {
            $donegenericmodule = $this->getDoneGenericModuleTable()->getDoneGenericModule($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('zfcadmin', array(
                'action' => 'index'
            ));
        }
        
        $form = new DoneGenericModuleForm($doneform->getWhatToAdd(), $name = null);

        $form->bind($donegenericmodule);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($donegenericmodule->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $return = $donegenericmodule->exchangeArrayEdit($form->getData());
                $this->getDoneGenericModuleTable()->saveDoneGenericModuleEdit($donegenericmodule, $return, $tablename);

                // Redirect to list of donegenericmodules
                return $this->redirect()->toRoute('zfcadmin');
            }
        }

        
        return new ViewModel(array(
            'doneform' => $doneform,
            'id' => $id,
            'form' => $form
        ));
    }

    public function deleteAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('zfcadmin');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getDoneGenericModuleTable()->deleteDoneGenericModule($id);
             }

             // Redirect to list of donegenericmodules
             return $this->redirect()->toRoute('zfcadmin');
         }

         return array(
             'id'    => $id,
             'donegenericmodule' => $this->getDoneGenericModuleTable()->getDoneGenericModule($id)
         );
     }
    public function getDoneGenericModuleTable()
    {
        if (!$this->donegenericmoduleTable) {
            $sm = $this->getServiceLocator();
            $this->donegenericmoduleTable = $sm->get('DoneGenericModule\Model\DoneGenericModuleTable');
        }
        return $this->donegenericmoduleTable;
    }
    private function getFields($form_or_result, $fieldTable, $readable_id){

        $tablename = array();
        $readableIDFields = array();
        $titleFields = array();  
        foreach ($fieldTable as $ft){
            if($readable_id === $ft[$form_or_result.'_readable_id']){
                $tablename[] = $ft['table_readable_id_DB'];
                $readableIDFields[] = $ft['field_readable_id'];
                $titleFields[] = $ft['field_title'];
            }
        }

        $doneresult = new DoneResult();
        $doneresult->setReadableIDFields($readableIDFields);
        $doneresult->setTitleFields($titleFields);
        $doneresult->_inizialize($readable_id);
        
        return array(
            'doneresult' => $doneresult,
            'table_readable_id_DB' => $tablename,
            'readableIDFields' => $readableIDFields,
            'titleFields' => $titleFields
            ) ;
    }
}