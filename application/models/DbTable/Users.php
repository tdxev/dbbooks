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
     * @param string $activation_key md5 hash used for account validation
     * @return int Last inserted id 
     */
    function addNew($username, $password, $email, $webpage, $activation_key)
    {
      $row = $this->createRow();
      $row->username = $username;
      $row->password = md5($password);
      $row->email    = $email;
      $row->webpage  = $webpage;
      $row->registration_date = new Zend_Db_Expr('NOW()');
      $row->registration_ip = $_SERVER['REMOTE_ADDR'];
      $row->group    = 2;
      $row->activation_key = $activation_key;
      try 
      {
        return $row->save();
      }
      catch (Exception $e)
      {
        return false;
      } 
    }

    
    /**
     * Activate user account
     *
     * @param string $email User email
     * @param string $key User activation key
     */
    function activateAccount($email, $key)
    {
      $query = $this->select()->from($this->_name, array('id'))->where('email = ?', $email)->where('activation_key = ?', $key);
      $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
      $user = $this->_db->fetchRow($query->__toString());
      // -- if exist a user with a email and activation key delete activation key
      if ($user){
        $data = array(
          'activation_key' => ''
        );
        $where = $this->getAdapter()->quoteInto('id = ?', $user->id);
        return $this->update($data, $where);
      }
      else
      {
        return false;
      }
    }
    
    
    /**
     * Return user by username
     * 
     * @param string $username
     * @return array 
     */
    function getUserByUsername($username)
    {
      return $this->fetchRow($this->select()->where('username = ?', $username));
    }
    
    /**
     * Return user by e-mail address
     * 
     * @param string $email
     * @return array 
     */
    function getUserByEmail($email)
    {
      return $this->fetchRow($this->select()->where('email = ?', $email));
    }
}

