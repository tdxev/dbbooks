<?php

class Application_Model_DbTable_Subcategories extends Zend_Db_Table_Abstract
{

    protected $_name = 'subcategories';

    /**
     * Return all subcategories that belong to a category
     * 
     * @param int $category_id Primary key for category
     * @return array
     */
    public function getSubcategoriesByCategory($category_id)
    {
      return $this->_fetch(
        $this ->select()
              ->from($this->_name, array('id', 'subcategory_name'))
              ->where('category_id = ?', (int) $category_id)
      );
    }

    
    /**
     * Check if subcategory exist for a category
     * 
     * @param type $category_id
     * @param type $subcategory_name 
     * @return boolean
     */
    public function existSubcategory($category_id, $subcategory_name)
    {
      return $this->_fetch(
        $this->select()
             ->where('category_id = ?', (int) $category_id)
             ->where('subcategory_name = ?', $subcategory_name)
      );
    }


    
    
    /**
     * Add new subcategory to database
     * 
     * @param int $category_id Primari key for category 
     * @param string $subcategory_name Subcategory name
     * @retun int last inserted id
     */
    public function addSubcategory($category_id, $subcategory_name)
    {
      // check if subcategory don`t exist for given category
      if (!$this->existSubcategory($category_id, $subcategory_name))
      {
        $html_filter = new Zend_Filter_HtmlEntities();
        $row = $this->createRow();
        $row->user_id = (int) Zend_Auth::getInstance()->getIdentity()->id;
        $row->category_id = (int) $category_id;
        $row->subcategory_name = $html_filter->filter($subcategory_name);
        try
        {
          return $row->save();
        }
        catch (Exception $e)
        {
          return false;
        }
      }
      else
      {
        return false;
      }
    }
    
}

