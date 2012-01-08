<?php

class Application_Form_Download extends Zend_Form
{

    public function init()
    {
      //$this->setAction('');
      
      /* Form Elements & Other Definitions Here ... */
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
      
      $this->addElement('submit','download',array(
        'label' => 'Download',
        'class' => 'button medium white',
        'ignore' => true
      ));
    }
}

