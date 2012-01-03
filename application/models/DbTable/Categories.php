<?php

class Application_Model_DbTable_Categories extends Zend_Db_Table_Abstract
{

    protected $_name = 'categories';
    
    /**
     * Get all categories
     */
    public function getAll()
    {
      $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
      return $this->_db->fetchAll($this->select());
    }
    
    /**
     * Add new category to database
     * 
     * @param string $category_name Category name
     * @retun int last inserted id
     */
    public function addCategory($category_name)
    {
      $html_filter = new Zend_Filter_HtmlEntities();

      $row = $this->createRow();
      $row->user_id = (int) Zend_Auth::getInstance()->getIdentity()->id;
      $row->category_name = $html_filter->filter($category_name);
      try
      {
        return $row->save();
      }
      catch (Exception $e)
      {
        return false;
      }
    }
}

