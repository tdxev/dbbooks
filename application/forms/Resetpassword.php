<?php

class Application_Form_Resetpassword extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
      $this->setMethod('post');

      $this->addElement('text','email',array(
        'label' => 'E-mail: ',
        'allowEmpty' => false,
        'validators' => array('notEmpty','emailAddress'),
        'required' => true
      ));
      
      $this->addElement('submit','signup',array(
        'label' => 'Reset password',
        'class' => 'button medium white',
        'ignore' => true
      ));
    }


}

