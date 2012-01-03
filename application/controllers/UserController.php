<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
      $this->view->title = "Users";
    }

    public function __call($methodName, $args)
    {
      // when user call action that not exist dispaly index page
      $this->_forward('index','index');
    }
    
    public function indexAction()
    {
      // nothing for users index redirect to site base index
      if(!Zend_Auth::getInstance()->hasIdentity())
      {
        $this->_helper->redirector('login', 'user');
      }
    }

    /**
     * Add new user to database
     */
    public function signupAction()
    {
      $this->view->title = 'User Singup';
      $form = new Application_Form_Signup();
      
      
      if($this->_request->isPost() && $form->isValid($_POST))
      {
        $errors = false;
        $users = new Application_Model_DbTable_Users();

        // if username already exist in database
        if($users->getUserByUsername($form->getValue('username')))
        {
          $errors = true;
          $form->getElement('username')->addError('This name is used by another user');
        }
        
        // if username already exist in database
        if($users->getUserByEmail($form->getValue('email')))
        {
          $errors = true;
          $form->getElement('email')->addError('This mail is used by another user');
        }

        if (!$errors)
        {
          $user = $users->addNew(
            $form->getValue('username'), 
            $form->getValue('password'), 
            $form->getValue('email'),
            $form->getValue('webpage')
          );
          if ($user) 
          {
            $this->_helper->redirector('login','user');
            //TODO send mail to validate  email acount that has beeb used
          }
          
        }
      }
      
      $this->view->form = $form;
    }

    /**
     * User Login
     */
    public function loginAction()
    {
      $this->view->title = 'User Login';
      $form = new Application_Form_Login();
      
      //if user is already logged in
      if(Zend_Auth::getInstance()->hasIdentity())
      {
        $this->_forward('index','index');
      }
      
      if($this->_request->isPost())
      {
        if($form->isValid($_POST))
        {
          $auth = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter(), 'users', 'username', 'password', 'md5(?)');
          $auth->setIdentity($form->getValue('username'));
          $auth->setCredential($form->getValue('password'));
          
          $result = $auth->authenticate();
          if($result->isValid())
          {
            // the pair username and password are good
            $storage = Zend_Auth::getInstance()->getStorage();
            $storage->write($auth->getResultRowObject(array('id', 'username', 'email', 'webpage')));
            $this->_helper->redirector('index','user');
          }
          else
          {
            $form->getElement('username')->addError('Bad username or password.');
            $form->getElement('password')->addError('Bad username or password.');
          }
        }
      }
      $this->view->form = $form;
    }

    /**
     * Logout user form application
     */
    public function logoutAction()
    {
      Zend_Auth::getInstance()->clearIdentity();
      $this->_helper->redirector('index','index');
    }
}





