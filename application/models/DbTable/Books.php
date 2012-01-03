<?php
class Application_Model_DbTable_Books extends Zend_Db_Table_Abstract
{

    protected $_name = 'books';

    /**
     * Add new book to database
     * 
     * @
     * @param string $title Book name
     * @param string $description Book description
     * @param string $hash Book hash
     * @param string $author Book author
     * @param int $language_id Primary key from languages
     * @param string $file_location Path to file
     * @param int $category Primary key form category
     * @param array $subcategories array of Primary keys form subcategory
     * @return int Last inserted id
     */
    public function addNew($title, $description, $author, $language_id, $file_location, $file_size, $hash, $category, $subcategories)
    {
      if (is_array($subcategories))
      {
        $subcategories = ',' . implode(',', $subcategories) . ',';
      }
      else
      {
        $subcategories = '';
      }
      
      $identity = Zend_Auth::getInstance()->getIdentity();
      $data = array(
        'title' => $title,
        'description' => $description,
        'content' => '',
        'author' => $author,
        'creator' => '',
        'producer' => '',
        'language_id' => (int) $language_id,
        'creation_date' => new Zend_Db_Expr('NOW()'),
        'modification_date' => new Zend_Db_Expr('NOW()'),
        'uploader' => (int) $identity->id,
        'file_location' => $file_location,
        'file_size' => $file_size,
        'file_hash' => $hash,
        'category_id' => (int) $category,
        'subcategories' => $subcategories
      );
      
      return $this->insert($data);
    }
    
    

    
    /**
     * Return all books in database
     * 
     * @return array DB-Books rows
     */
    function getAll()
    {
      $query = 'SELECT 
                  id, 
                  title, 
                  description, 
                  content, 
                  author, 
                  creator, 
                  producer, 
                  (SELECT `name` FROM languages WHERE books.language_id = languages.id) as language, 
                  creation_date,
                  modification_date, 
                  (SELECT `username` FROM users WHERE books.uploader = users.id ) as uploader,
                  file_hash, 
                  file_location, 
                  file_size, 
                  (SELECT category_name FROM categories WHERE books.category_id = categories.id) as category, 
                  (SELECT GROUP_CONCAT( subcategory_name ) FROM subcategories WHERE LOCATE( CONCAT( ",", subcategories.id, "," ) , books.subcategories ) >= "1") as subcategories
                FROM 
                  books
                ORDER BY
                  creation_date
                DESC';
      return $this->getAdapter()->fetchAll($query, array(), Zend_Db::FETCH_OBJ);
    }
    
    
    /**
     * Return book by primary key of the book
     * Make subselects to get user/category/subcategory..
     * 
     * @param int $book_id Primary Key for the book
     * @return array 
     */
    public function getBookDetailsByID($book_id)
    {
      $query = $this  ->select()
                      ->from($this->_name, array(
                        'id', 
                        'title', 
                        'description', 
                        'content', 
                        'author', 
                        'creator', 
                        'producer', 
                        'language' => '(SELECT `name` FROM languages WHERE books.language_id = languages.id)', 
                        'creation_date', 
                        'modification_date', 
                        'uploader' => '(SELECT `username` FROM users WHERE books.uploader = users.id)',
                        'file_size', 
                        'category' => '(SELECT category_name FROM categories WHERE books.category_id = categories.id)', 
                        'subcategories' => '(SELECT GROUP_CONCAT( subcategory_name ) FROM subcategories WHERE LOCATE( CONCAT( ",", subcategories.id, "," ) , books.subcategories ) >= "1")'
                      ))
                      ->where('`id` = ?', (int) $book_id);
      
      $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
      return $this->_db->fetchRow($query->__toString());
    }
    
    
    
    
    
    /**
     * Return book by md5 hash of book content
     * 
     * @param string $hash MD5 hash of the book
     * @return array 
     */
    public function getBookByHash($file_hash)
    {
      return $this->fetchRow($this->select()->where('`file_hash` = ?', $file_hash));
    }
    
    /**
     * Return book by primary key of the book
     * 
     * @param int $book_id Primary Key for the book
     * @return array 
     */
    public function getBookByID($book_id)
    {
      return $this->fetchRow($this->select()->where('`id` = ?', (int) $book_id));
    }

    
    /** 
     * Creeate LIKE conditon
     */
    private function put($column, $data)
    {
      // -- if no data
      if (is_null($data)) return ' true ';
      
      $search_list = array();
      $words = explode(' ', $data);
      if (is_array($words))
        foreach($words as $word)
          if (strlen($word))
          {
            $search_list[] = sprintf("`%s` LIKE '%%%s%%' ", mysql_real_escape_string($column), mysql_real_escape_string($word));
          }
      else
        return sprintf("`%s` LIKE '%%%s%%' ", mysql_real_escape_string($column), mysql_real_escape_string($word));
        
      if (count($search_list) > 0)
      {
        return implode( ' AND ', $search_list);
      }
      return ' true ';
    }
    
    
    
    
    /**
     * Search book in database 
     *
     * @param string $title
     * @param string $description
     * @param string $author
     * @param int $language_id
     * @param int $category
     * @param int $subcategories 
     * @return array
     */
    public function searchBook($title, $description, $author, $language_id, $category, $subcategories)
    {
      $qSelect = new Zend_Db_Select($this->getAdapter());
      $qSelect->from($this->_name, 
        array(
            new Zend_Db_Expr('SQL_CALC_FOUND_ROWS id'),
            'id',
            'title', 
            'description', 
            'content', 
            'author', 
            'creator', 
            'producer',
            'language' => '(SELECT `name` FROM `languages` WHERE books.language_id = languages.id)',
            'creation_date', 
            'modification_date', 
            'uploader' => '(SELECT username FROM `users` WHERE books.uploader = users.id )',
            'file_hash', 
            'file_location', 
            'file_size', 
            'category' => '(SELECT category_name FROM categories WHERE books.category_id = categories.id)', 
            'subcategories' => '(SELECT GROUP_CONCAT( subcategory_name ) FROM subcategories WHERE LOCATE( CONCAT( ",", subcategories.id, "," ) , books.subcategories ) >= "1")'
        )
      );
      

      $qSelect->where($this->put('title', $title));
      $qSelect->where($this->put('description', $description));
      $qSelect->where($this->put('author', $author));
      if ($language_id != 0 ) $qSelect->where($this->put('language_id', (int)$language_id));
      if ($category != 0 )    $qSelect->where($this->put('category_id', (int)$category));
      if (is_array($subcategories) && $subcategories[0] != 0)
      {
       $search_subcategories = ',' . implode(', ,',$subcategories) . ',';
       $qSelect->where($this->put('subcategories', $search_subcategories));
      }
      return $qSelect;
    }
    
}

