<?php

class Application_Form_Upload extends Zend_Form
{

    public function init()
    {
      
      $decorators = array(
        'ViewHelper',
        array('Description', array('escape' => false, 'tag' => false)),
        array('HtmlTag', array('tag' => 'dd')),
        array('Label', array('tag' => 'dt')),
        'Errors'
      );

      
      $this->setMethod('post');
      $this->setAttrib('id', 'uploadform');
     
      $this->addElement('file','book',array(
        'label' => 'Book: ',
        'destination' => Zend_Registry::get('config')->upload_directory,
        'validators' => array(
            array('count', false, 1),
            array('extension', false, 'pdf,doc,txt'),
            array('file_Size', true, 'option' => array('max'=>'50MB'))),
        'require' => true
      ));
      

      $this->addElement('text','title',array(
        'label' => 'Book Title: ',
        'require' => true,
        'allowEmpty' => false,
        'validators' => array('notEmpty'),
        'filters' => array('htmlEntities')
      ));
      
      $this->addElement('text','description',array(
        'label' => 'Book description: ',
        'require' => true,
        'allowEmpty' => false,
        'validators' => array('notEmpty'),
        'filters' => array('htmlEntities')
      ));
      
      $this->addElement('text','author',array(
        'label' => 'Book Author: ',
        'require' => true,
        'allowEmpty' => false,
        'validators' => array('notEmpty'),
        'filters' => array('htmlEntities')
      ));
      
      $this->addElement('select','language',array(
        'label' => 'Book language: ',
        'validators' => array('int'),
        'require' => true,
        'RegisterInArrayValidator' => false,
      ));
      
      $this->addElement('select','category',array(
        'description' => '<a href="javascript:addCategory()">Add category</a>',
        'decorators' => $decorators,
        'label' => 'Category: ',
        'validators' => array('int'),
        'require' => true,
        'RegisterInArrayValidator' => false,
      ));
          
      
      $this->addElement('multiselect','subcategory',array(
        'description' => '<a href="javascript:addSubcategory()">Add subcategory</a>',
        'decorators' => $decorators,
        'label' => 'Subcategories: ',
        'validators' => array('int'),
        'multiple' => 'multiple',
        'allowEmpty' => false,
        'validators' => array('notEmpty'),
        'RegisterInArrayValidator' => false,
        'require' => true
      ));
      
      $options = array('theme' => 'clean', 'lang' => 'en');
      $recaptcha = new Zend_Service_ReCaptcha(
          Zend_Registry::get('config')->recaptcha_public_key, 
          Zend_Registry::get('config')->recaptcha_private_key,
          null,
          $options
      );
      
      $this->addElement('Captcha', 'ReCaptcha',array(
        
        'captcha'=>array('captcha'=>'ReCaptcha','service'=>$recaptcha)
      ));
      
      
      
      $this->addElement('submit','upload',array(
        'label' => ' Upload Book ',
        'class' => 'button medium white',
        'ignore' => true
      ));
    }


}

