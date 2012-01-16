<?php

class Application_Form_Createnewpassword extends Zend_Form
{

    public function init()
    {
      /* Form Elements & Other Definitions Here ... */
      $this->setMethod('post');

      $this->addElement('hidden', 'email', array(
       'required' => true,
       'decorators' => array('ViewHelper'),
       'validators' => array('int'),
       'filters' => array('int')
      ));

      $this->addElement('hidden', 'key', array(
       'required' => true,
       'decorators' => array('ViewHelper'),
       'validators' => array('int'),
       'filters' => array('int')
      ));
      
      $this->addElement('password','password',array(
        'label' => 'Password: ',
        'allowEmpty' => false,
        'validators' => array('notEmpty',array('stringLength', true, 'option' =>array('min'=>6 ,'max'=>255))),
        'required' => true
      ));
      
      $this->addElement('password','repassword',array(
        'label' => 'Retype passsword: ',
        'allowEmpty' => false,
        'validators' => array('notEmpty',array('identical', false, array('token' => 'password'))),
        'required' => true
      ));
      
      $this->addElement('submit','createnewpassword',array(
        'label' => 'Reset password',
        'class' => 'button medium white',
        'ignore' => true
      ));
    }


}

