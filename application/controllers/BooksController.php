<?php

class BooksController extends Zend_Controller_Action
{

    public function __call($methodName, $args)
    {
      // when user call action that not exist dispaly index page
      $this->_forward('index','index');
    }

    public function init()
    {
      /* Initialize action controller here */
      $this->view->title = "Books";
    }

    public function indexAction()
    {
      $books = new Application_Model_DbTable_Books();
      $this->view->books = $books->getAll();
    }

    public function uploadAction()
    {
      //TODO edit php.ini to increase max size for POST/UPLOAD

      if(!Zend_Auth::getInstance()->hasIdentity())
      {
        $this->_helper->redirector('login', 'user');
      }

      
      $form = new Application_Form_Upload();
      if($this->_request->isPost() && $form->isValid($_POST) && $form->book->isUploaded())
      {
        $books = new Application_Model_DbTable_Books();
        if (!$books->getBookByHash($form->book->getHash('md5')))
        {
          // rename book to  uploadDir/bookMD5Hash_FileName.EXT
          $form->book->addFilter('Rename',array(
            'target' => Zend_Registry::get('config')->upload_directory . 
                        $form->book->getHash('md5') . '_' .
                        $form->book->getFileName('book', false))
          );
          $form->book->receive();
          $books->addNew(
            $form->getValue('title'), 
            $form->getValue('description'), 
            $form->getValue('author'), 
            $form->getValue('language'), 
            $form->book->getFileName(), 
            $form->book->getFileSize(), 
            $form->book->getHash('md5'), 
            $form->getValue('category'),
            $form->getValue('subcategory')
          );
          $this->_forward('index','books');
        }
        else
        {
          $form->book->addError('This book already exist in database');
        }
      }
      else
      {
        // -- get all categories
        $categories = new Application_Model_DbTable_Categories();
        $category_options = array();
        foreach($categories->getAll() as $row)
        {
          $category_options[$row->id] = $row->category_name;
        }

        $selected_category = (int) $form->getValue('category');
        if ($selected_category == 0)
        {
          $first_category = array_keys($category_options);
          $selected_category = $first_category[0];
        }

        // -- get all subcategories
        $subcategories = new Application_Model_DbTable_Subcategories();
        $subcategory_options = array();
        foreach($subcategories->getSubcategoriesByCategory($selected_category) as $row)
        {
          $subcategory_options[$row['id']] = $row['subcategory_name'];
        }

        // -- get all languages
        $languages = new Application_Model_DbTable_Languages();
        $language_options = array();
        foreach($languages->getAll() as $row)
        {
          $language_options[$row['id']] = $row['name'];
        }


        $form->getElement('category')->addMultiOptions($category_options);
        $form->getElement('subcategory')->addMultiOptions($subcategory_options);
        $form->getElement('language')->addMultiOptions($language_options);
      }
      $this->view->form = $form;
    }

    public function searchAction()
    {
      $form = new Application_Form_SearchBook();
      
     
      if($this->_request->isPost() && $form->isValid($_POST))
      {
        $books = new Application_Model_DbTable_Books();
        $qSelect = $books->searchBook(
          $form->getValue('title'), 
          $form->getValue('description'), 
          $form->getValue('author'), 
          $form->getValue('language'), 
          $form->getValue('category'), 
          $form->getValue('subcategory')
        );
        
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($qSelect));
        $paginator->setItemCountPerPage(($this->_getParam('resultsonpage', 1) * 10))
                  ->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->books = $paginator;

        // -- get all languages
        $languages = new Application_Model_DbTable_Languages();
        $language_options = array('0' => 'Any language');
        foreach($languages->getAll() as $row)
        {
          $language_options[$row['id']] = $row['name'];
        }

        // -- get all categories
        $categories = new Application_Model_DbTable_Categories();
        $category_options = array('0' => 'Any category');
        foreach($categories->getAll() as $row)
        {
          $category_options[$row->id] = $row->category_name;
        }
        
        // -- get all subcategories
        if ((int)$form->getValue('category') != '0')
        {
          $subcategories = new Application_Model_DbTable_Subcategories();
          $subcategory_options = array();
          foreach($subcategories->getSubcategoriesByCategory((int) $form->getValue('category') ) as $row)
          {
            $subcategory_options[$row['id']] = $row['subcategory_name'];
          }
        }
        else
        {
          $subcategory_options = array('0' => 'Any Subcategory');
        }
        
        $form->getElement('language')->addMultiOptions($language_options);
        $form->getElement('category')->addMultiOptions($category_options);
        $form->getElement('subcategory')->addMultiOptions($subcategory_options);
      }
      else
      {
        // -- get all languages
        $languages = new Application_Model_DbTable_Languages();
        $language_options = array('0' => 'Any language');
        foreach($languages->getAll() as $row)
        {
          $language_options[$row['id']] = $row['name'];
        }

        // -- get all categories
        $categories = new Application_Model_DbTable_Categories();
        $category_options = array('0' => 'Any category');
        foreach($categories->getAll() as $row)
        {
          $category_options[$row->id] = $row->category_name;
        }
        $subcategory_options = array('0' => 'Any Subcategory');

        
        $form->getElement('language')->addMultiOptions($language_options);
        $form->getElement('category')->addMultiOptions($category_options);
        $form->getElement('subcategory')->addMultiOptions($subcategory_options);
        
      }
      $this->view->form = $form;
    }

    public function detailsAction()
    {
      // action body
      $books = new Application_Model_DbTable_Books();
      $form = new Application_Form_Download();
      if ($this->_request->getParam('name') != '')
      {
        // get book md5 hash
        $book_hash = substr($this->_request->getParam('name'),0,32);
        $book = $books->getBookDetailsByHash($book_hash);
        if ($book)
        {
          if ($this->_request->isPost() && $form->isValid($_POST))
          {
            $book = $books->getBookByID($book->id);
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
          
          
          $this->view->hidedatails  = (boolean) $this->_request->getParam('hidedatails'); 
          $this->view->book = $book;
          $this->view->form = $form;
        }
        else
        {
        }
      }
    }

}









