<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
      /* Form Elements & Other Definitions Here ... */

      $decorators = array(
        'ViewHelper',
        array('Description', array('escape' => false, 'tag' => 'p')),
        array('HtmlTag', array('tag' => 'dd')),
        array('Label', array('tag' => 'dt')),
        'Errors'
      );
      
      $this->setMethod('post');

      $this->addElement('text','username',array(
        'label' => 'User Name: ',
        'allowEmpty' => false,
        'validators' => array('notEmpty', array('stringLength', true, 'option' =>array('min'=>2 ,'max'=>50))),
        'required' => true
      ));

      $view = Zend_Layout::getMvcInstance()->getView();
      
      $this->addElement('password','password',array(
        'label' => 'Password: ',
        'allowEmpty' => false,
        'validators' => array('notEmpty', array('stringLength', true, 'option' =>array('min'=>6 ,'max'=>255))),
        'required' => true,
        'decorators' => $decorators,
        'description' => '<a href="'.$view->baseUrl().'/user/resetpassword/'.'">Forgot Password</a>'
      ));

      $this->addElement('submit','login',array(
        'label' => 'Login',
        'class' => 'button medium white',
        'ignore' => true
      ));
    }


}

