<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
      $this->setMethod('post');

      $this->addElement('text','username',array(
        'label' => 'User Name: ',
        'allowEmpty' => false,
        'validators' => array('notEmpty', array('stringLength', true, 'option' =>array('min'=>2 ,'max'=>50))),
        'required' => true
      ));

      
      $this->addElement('password','password',array(
        'label' => 'Password: ',
        'allowEmpty' => false,
        'validators' => array('notEmpty', array('stringLength', true, 'option' =>array('min'=>6 ,'max'=>255))),
        'required' => true
      ));

      $this->addElement('submit','submit',array(
        'label' => 'Login',
        'class' => 'button medium white',
        'ignore' => true
      ));
    }


}

