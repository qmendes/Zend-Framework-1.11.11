<?php

class Application_Model_ClientsMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Um erro ocorreu! Tente novamente mais tarde.');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Clients');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Clients $client)
    {
        $data = array(
            'name' => $client->getName(),
            'email'   => $client->getEmail(),
            'telefone' => $client->getTelefone(),
            'cpf' => $client->getCpf(),
        );

        if ( null === ($id = $client->getId()) or $id == '0' ) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_Clients $client)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $client->setId($row->id)
            ->setName($row->name)
            ->setEmail($row->email)
            ->setTelefone($row->telefone)
            ->setCpf($row->cpf);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Clients();
            $entry->setId($row->id)
                ->setName($row->name)
                ->setEmail($row->email)
                ->setTelefone($row->telefone)
                ->setCpf($row->cpf);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function getClient($id)
    {
        $id = (int)$id;
        $clients = new Application_Model_DbTable_Clients();
        $row = $clients->fetchRow('id = ' . $id);
        if (!$row){
            throw new Exception("Erro ao buscar cliente!");
        }
        return $row->toArray();
    }

    public function validaCPF($cpf) {

        // Verifica se um número foi informado
        if(empty($cpf)) {
            return false;
        }

        // Elimina possivel mascara
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Verifica se o numero de digitos informados é igual a 11
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999') {
            return false;
            // Calcula os digitos verificadores para verificar se o
            // CPF é válido
        } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

}

