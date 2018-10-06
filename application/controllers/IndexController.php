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

}

