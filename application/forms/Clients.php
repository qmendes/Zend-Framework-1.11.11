<?php

class Application_Form_Clients extends Zend_Form
{

    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

        $this->addElement('hidden', 'id');

        // Add the name element
        $this->addElement('text', 'name', array(
            'label'      => 'Nome:',
            'required'   => true,
            'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 200))
            )
        ));

        // Add an email element
        $this->addElement('text', 'email', array(
            'label'      => 'E-mail:',
            'required'   => true,
            'filters'    => array('StringTrim','StringtoLower'),
            'validators' => array(
                'EmailAddress',
            )
        ));


        $this->addElement('text', 'telefone', array(
            'label'      => 'Telefone:',
            'required'   => true,
            'validators' => array(
                array('Regex',
                    false,
                    array('/^[0-9]/'))
            )
        ));

        $this->addElement('text', 'cpf', array(
            'label'      => 'CPF:',
            'required'   => true,
        ));


        // Add a captcha
        $this->addElement('captcha', 'captcha', array(
            'label'      => 'Por favor, informe as 5 letras abaixo:',
            'required'   => true,
            'autocomplete' => 'off',
            'captcha'    => array(
                'captcha' => 'Figlet',
                'wordLen' => 5,
                'timeout' => 300
            )
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Salvar',
        ));

        // And finally add some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }


}

