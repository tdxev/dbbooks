<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{


  protected function _initCreateMenu()
  {
    $menu['title'] = 'Menu';
    
    if(Zend_Auth::getInstance()->hasIdentity())
    {
      $menu['content']['/books/search/'] =  'Search Book';
      $menu['content']['/books/upload/'] =  'Upload Book';
      $menu['content']['/user/logout/']  =  'Logout';
    }
    else
    {
      $menu['content']['/books/search/'] =  'Search Book';
      $menu['content']['/user/login/']   =  'Login';
      $menu['content']['/user/signup/']  =  'Signup';
    }
    
    Zend_Registry::set("aside", $menu);
  }
  
  
  protected function _initConfig()
  {
      $config = new Zend_Config($this->getOptions(), true);
      Zend_Registry::set('config', $config);
  }
}

