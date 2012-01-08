<?php

class AjaxController extends Zend_Controller_Action
{

  public function init()
  {
      /* Initialize action controller here */
  }

  /**
   * Create a custom error when the command send is invalid
   * 
   * @param string $methodName
   * @param array $args 
   */
  public function __call($methodName, $args)
  { 
    $this->ajaxerror("Invalid command!");
  }


  /**
   * Return error message
   *
   * @param string $msg // Error message that will be send back
   */
  private function ajaxerror($msg){
    $ajax_result = array('error' => 1, 'msg' => $msg);
    echo json_encode($ajax_result);
    exit(0);
  }

  /**
   * Return result
   *
   * @param string $result_name Name of the result
   * @param mixed $data Result that will be returned
   */
  private function ajaxok($result_name, $result_data){
    $ajax_result = array('error' => 0, $result_name => $result_data);
    echo json_encode($ajax_result);
    exit(0);
  }
  
  /**
   * Display parameters as JSON
   * 
   * @param array $data Array of params that will be send back
   */
  private function display_json($data){
    echo json_encode($data);
    exit(0);
  }

  public function indexAction()
  {
    // action body
    $input = $this->getRequest();

    if ($input->isPost())
    {
      $in = json_decode($input->data);
      $this->{$in->cmd}($in->params);
    }
    exit(0);
  }

  /**
   * Try to add new category to database then return all categories
   * 
   * @param object $params Parameters send to the server
   */
  private function add_category($params)
  {
    // check if user is login
    if (!Zend_Auth::getInstance()->hasIdentity())
    {
      $this->ajaxerror('You must be logged in to add a new category!');
    }
    
    $categories = new Application_Model_DbTable_Categories();
    if($categories->addCategory($params->category_name))
    {
      $category_list  = $categories->getAll();
      $this->ajaxok('categories', $category_list);
    }
    else
    {
      $this->ajaxerror('This category already exist in database! ');
    }
  }

  
  /**
   * Try to add new subcategory to database then return all subcategories
   * 
   * @param object $params Parameters send to the server
   */
  private function add_subcategory($params)
  {
    // check if user is login
    if (!Zend_Auth::getInstance()->hasIdentity())
    {
      $this->ajaxerror('You must be logged in to add a new category!');
    }
    
    $subcategories = new Application_Model_DbTable_Subcategories();
    if ($subcategories->addSubcategory($params->category_id, $params->subcategory_name))
    {
      $subcategory_list  = $subcategories->getSubcategoriesByCategory($params->category_id);
      $this->ajaxok('subcategories', $subcategory_list);
    }
    else
    {
      $this->ajaxerror('This subcategory already exist in database! '); 
    }
  }


  /**
   * Return all subcategories of a category
   * 
   * @param object $params Parameters send to the server
   */
  private function get_childrens_off_category($params)
  {
    $subcategories = new Application_Model_DbTable_Subcategories();
    $subcategory_list = $subcategories->getSubcategoriesByCategory($params->category_id);
    $this->ajaxok('subcategories', $subcategory_list);
  }

  /**
   * Return book details by book id
   * 
   * @param object $params Parameters send to the server
   */
  private function get_book_details($params)
  {
    $books = new Application_Model_DbTable_Books();
    $book = $books->getBookDetailsByID($params->book_id);
    $this->ajaxok('book', $book);
  }

}

