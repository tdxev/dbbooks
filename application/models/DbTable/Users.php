<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

    /**
     * Add a new user to database
     * 
     * @param string $username
     * @param string $password
     * @param string $email
     * @return int Last inserted id 
     */
    function addNew($username, $password, $email, $webpage)
    {
      $row = $this->createRow();
      $row->username = $username;
      $row->password = md5($password);
      $row->email    = $email;
      $row->webpage  = $webpage;
      $row->registration_date = new Zend_Db_Expr('NOW()');
      $row->registration_ip = $_SERVER['REMOTE_ADDR'];
      $row->group    = 2;
      try 
      {
        return $row->save();
      }
      catch (Exception $e)
      {
        return false;
      }
      
    }
    
    function getUserByUsername($username)
    {
      return $this->fetchRow($this->select()->where('username = ?', $username));
    }
    
    function getUserByEmail($email)
    {
      return $this->fetchRow($this->select()->where('email = ?', $email));
    }
}

