<?php

class Application_Form_SearchBook extends Zend_Form
{

    public function init()
    {
      $this->setMethod('post');
      $this->setAttrib('id', 'searchform');

      $this->addElement('hidden', 'page', array(
       'value' => 1,
       'required' => true,
       'validators' => array('int'),
       'filters' => array('int')
      ));
      
      $search_text_list = array('title', 'description', 'author');
      foreach($search_text_list as $item)
      {
        $this->addElement('text', $item, array(
         'label' => ucfirst($item),
        ));
      }

      $this->addElement('select','language',array(
        'label' => 'Book language: ',
        'validators' => array('int'),
        'RegisterInArrayValidator' => false,
      ));
      
      $this->addElement('select','category',array(
        'label' => 'Category: ',
        'validators' => array('int'),
        'RegisterInArrayValidator' => false,
      ));
          
      $this->addElement('multiselect','subcategory',array(
        'label' => 'Subcategories: ',
        'validators' => array('int'),
        'multiple' => 'multiple',
        'RegisterInArrayValidator' => false,
      ));

      $this->addElement('select','resultsonpage',array(
        'label' => 'Results on page: ',
        'validators' => array('int'),
        'RegisterInArrayValidator' => false,
        'MultiOptions' => array_merge(array(0 => 'All'),range(10,100,10)),
        'value' => 1
      ));
      
      $this->addElement('submit','search',array(
        'label' => 'Search',
        'class' => 'button medium white',
        'ignore' => true
      ));
    }


}

