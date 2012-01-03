<?php

class DownloadController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
      if($this->_request->isPost())
      {
        if (isset($this->_request->file_id))
        {  
          $books = new Application_Model_DbTable_Books();
          $book = $books->getBookByID($this->_request->file_id);
        if (file_exists($book->file_location))
          {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $book->title . '"');
            readfile($book->file_location);
          }
          else
          {
            header('HTTP/1.1 404 Not Found');
            echo "File not found!";
          }
          $this->view->layout()->disableLayout();
          $this->_helper->viewRenderer->setNoRender(true);
        }
      }
    }


}

