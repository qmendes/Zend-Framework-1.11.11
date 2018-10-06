<?php

class ClientsController extends Zend_Controller_Action
{


    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {

        if(file_exists($_SESSION['filename'])){
            $lines = file($_SESSION['filename']);
            $this->view->entries = $lines;
        }

    }

    public function signAction()
    {
        $request = $this->getRequest();
        $form    = new Application_Form_Clients();
        $message = "";

        $lines = file($_SESSION['filename']);
        foreach ($lines as $line) {
            $fields = explode(",", $line);
            $arrid[$fields[0]]=$fields[0];
            $arrcpf[$fields[0]]=trim((string)$fields[4]);
        }

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $clients = new Application_Model_Clients($form->getValues());
                $mapper  = new Application_Model_ClientsMapper();
                $message = "";

                //Verifica se CPF Já está cadastrado
                if (in_array($clients->getCpf(),$arrcpf))
                    $message = "###### Cliente já cadastrado. ######";

                //Validação de CPF
                if( $message == "" and $mapper->validaCPF($clients->getCpf()) === false )
                    $message = "###### Por favor, informe um CPF válido. ######";

                if($message == ""){
                    $csv = fopen($_SESSION['filename'] , 'a+');
                    if (flock($csv, LOCK_EX)) {
                        $linha = array(
                            max($arrid)+1,
                            trim($clients->getName()),
                            trim($clients->getEmail()),
                            trim($clients->getTelefone()),
                            trim($clients->getCpf()));

                        fwrite($csv, implode(',',$linha) . "\n");
                        flock($csv, LOCK_UN);
                    }
                    fclose($csv);
                    return $this->_helper->redirector('index');
                }

            }
        }

        $this->view->form = $form;
        $this->view->message = $message;
    }


    public function editAction()
    {

        $form = new Application_Form_Clients();
        $form->submit->setLabel('Salvar');
        $this->view->form = $form;

        $lines = file($_SESSION['filename']);
        foreach ($lines as $line) {
            $fields = explode(",", $line);
            $arrcli[$fields[0]]=$fields[0].",".$fields[1].",".$fields[2].",".$fields[3].",".$fields[4];
            $arrid[$fields[0]]=$fields[0];
            $arrcpf[$fields[0]]=trim((string)$fields[4]);
        }

        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if($form->isValid($formData)){
                $clients = new Application_Model_Clients($form->getValues());
                $mapper  = new Application_Model_ClientsMapper();

                $id = $clients->getId();
                $message = "";

                //Verifica se CPF existe
                $key = array_search($clients->getCpf(),$arrcpf);
                if ($key <> false and $key <> $clients->getId())
                    $message = "###### Este CPF já está cadastrado para outro cliente. ######";

                //Validação de CPF
                if( $message == "" and $mapper->validaCPF($clients->getCpf()) === false )
                    $message = "###### Por favor, informe um CPF válido. ######";

                if($message == ""){

                    $csv = fopen($_SESSION['filename'] , 'w');
                    if (flock($csv, LOCK_EX)) {
                        foreach ($arrcli as $key => $value) {
                            if($key == $clients->getId()){
                                $value =
                                    $clients->getId().","
                                    .trim($clients->getName()).","
                                    .trim($clients->getEmail()).","
                                    .trim($clients->getTelefone()).","
                                    .trim($clients->getCpf());
                                fwrite($csv, $value . "\n");
                            }else{
                                fwrite($csv, $value);
                            }
                        }
                        flock($csv, LOCK_UN);
                    }
                    fclose($csv);
                    $this->_helper->redirector('index');
                }else{
                    echo $message;
                }

            }else{
                $form->populate($formData);
            }

        }else{
            $id = $this->_getParam('id', 0);
            if ($id > 0){
                $arr = explode(",",$arrcli[$id]);
                $result['id']=$arr[0];
                $result['name']=str_replace('"','',$arr[1]);
                $result['email']=$arr[2];
                $result['telefone']=$arr[3];
                $result['cpf']=$arr[4];
                $form->populate($result);

            }
        }

    }

    public function deleteAction()
    {

        $lines = file($_SESSION['filename']);
        foreach ($lines as $line) {
            $fields = explode(",", $line);
            $arrcli[$fields[0]]=$fields[0].",".$fields[1].",".$fields[2].",".$fields[3].",".$fields[4];
        }

        if($this->getRequest()->isPost()){
            $del = $this->getRequest()->getPost('del');

            if($del == 'Sim'){
                $id = $this->getRequest()->getPost('id');
                $csv = fopen($_SESSION['filename'] , 'w');
                if (flock($csv, LOCK_EX)) {
                    foreach ($arrcli as $key => $value) {
                        if($key <> $id){
                            fwrite($csv, $value);
                        }
                    }
                    flock($csv, LOCK_UN);
                }
                fclose($csv);
            }
            $this->_helper->redirector('index');

        }else{
            $id = $this->_getParam('id', 0);
            $arr = explode(",",$arrcli[$id]);
            $result['id']=$arr[0];
            $result['name']=str_replace('"','',$arr[1]);
            $result['email']=$arr[2];
            $result['telefone']=$arr[3];
            $result['cpf']=$arr[4];
            $this->view->client = $result;
        }

    }


}



