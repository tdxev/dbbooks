<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
      /* Initialize action controller here */
    }

    public function indexAction()
    {
      $books = new Application_Model_DbTable_Books();
      $this->view->books = $books->getAll(10);
      
      $this->view->top_uploaders = $books->getTopUploaders(10);
    }



}









