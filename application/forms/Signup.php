<?php

class Application_Form_Signup extends Zend_Form
{

    public function init()
    {
      $this->setMethod('post');
      
      $this->addElement('text','username',array(
        'label' => 'User name: ',
        'allowEmpty' => false,
        'validators' => array('notEmpty','alnum', array('stringLength', true, 'option' =>array('min'=>2 ,'max'=>50))),
        'required' => true
      ));
      
      $this->addElement('text','email',array(
        'label' => 'E-mail: ',
        'allowEmpty' => false,
        'validators' => array('notEmpty','emailAddress'),
        'required' => true
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
      
      $this->addElement('text','webpage',array(
        'label' => 'User web page:',
        'required' => true,
        'filters' => array('htmlEntities')
      ));
            
      $this->addElement('submit','signup',array(
        'label' => 'Signup',
        'class' => 'button medium white',
        'ignore' => true
      ));
    }


}

