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
     * @return mixed
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
     * Update user password 
     * 
     * @param integer $user_id Primary key for user
     * @param string $new_password New password (palin text)
     * @return mixed
     */
    function updatePassword($user_id, $new_password)
    {
      
      
    }
    
    /**
     * Creeate a new password for a user that request a password reset
     * 
     * @param string $email User email
     * @param string $key User reset key sent by email (is keeped in  activation_key until user reset password)
     * @param string $newPassword New user password (plain text)
     * @return boolean 
     */
    function resetPasswordAddNew($email, $key, $newPassword)
    {
      $query = $this->select()->from($this->_name, array('id'))->where('email = ?', $email)->where('activation_key = ?', $key);
      $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
      $user = $this->_db->fetchRow($query->__toString());
      // -- if exist a user with a email and activation key delete activation key
      
      if ($user){
        
        $data = array(
          'activation_key' => '',
          'password' => $this->generatePassword($newPassword)
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
     * Reset user password
     * 
     * @param string $user_id
     * @return boolean
     */
    function resetPassword($user_id)
    {
      $user = $this->getUserByID($user_id);
      // -- if user exit
      if ($user)
      {
        $reset_key  = $this->generateHashKey();
        $data = array(
          'password' => '',
          'activation_key' => $reset_key
        );
        $where = $this->getAdapter()->quoteInto('id = ?', $user->id);
        $this->update($data, $where);
        return $reset_key;
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
    
     /**
     * Return user by e-mail id
     * 
     * @param integer $id
     * @return array 
     */
    function getUserByID($id)
    {
      return $this->fetchRow($this->select()->where('id = ?', $id));
    }
    
    
    /**
     * Generate a random key base on time 
     */
    public function generateHashKey()
    {
      return md5(md5(time()));
    }

    /**
     * Genereate password hash
     * 
     * @param string $password Plain text password
     * @return string MD5 hash (will be modified)  //TODO Add salt for passwords
     */
    public function generatePassword($password)
    {
      return md5($password);
    }
    
}

