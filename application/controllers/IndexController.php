<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {

        if(strpos(APPLICATION_PATH,"/") === false){$b = "\\";}else{$b = "/";}
        $filename = str_replace("application","public".$b."base.csv", APPLICATION_PATH);
        $_SESSION['filename'] = $filename;
        if (Zend_Loader::isReadable($filename) === false) {

        }

    }



    /*
    public function addAction()
    {
        $form = new Application_Form_Client();
        $form->submit->setLabel('Add');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if($form->isValid($formData)){
                $name = $form->getvalue('name');
                $email = $form->getvalue('email');
                $telefone = $form->getvalue('telefone');
                $cpf = $form->getvalue('cpf');
                $clients = new Application_Model_DbTable_Clients();
                $clients->addClient($name, $email, $telefone, $cpf);

                $this->_helper->redirector('index');

            }else{
                $form->populate($formData);
            }

        }

    }

    public function editAction()
    {
        $form = new Application_Form_Client();
        $form->submit->setLabel('Save');
        $this->view->form - $form;

        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if($form->isValid($formData)){
                $id = (int)$form->getValue('id');
                $name = $form->getvalue('name');
                $email = $form->getvalue('email');
                $telefone = $form->getvalue('telefone');
                $cpf = $form->getvalue('cpf');
                $clients = new Application_Model_DbTable_Clients();
                $clients->updateClient($id, $name, $email, $telefone, $cpf);

                $this->_helper->redirector('index');
            }else{
                $form->populate($formData);
            }

        }else{
            $id = $this->_getParam('id', 0);
            if ($id > 0){
                $clients = new Application_Model_DbTable_Clients();
                $form->populate($clients->getClient($id));
            }
        }

    }

    public function deleteAction()
    {
        if($this->getRequest()->isPost()){
            $del = $this->getRequest()->getPost('del');
            if($del == 'Yes'){
                $id = $this->getRequest()->getPost('id');
                $clients = new Application_Model_DbTable_Clients();
                $clients->deleteClient($id);
            }
            $this->_helper->redirector('index');
        }else{
            $id = $this->_getParam('id', 0);
            $clients = new Application_Model_DbTable_Clients();
            $this->view->client = $clients->getClient($id);
        }

    }
    */

}

