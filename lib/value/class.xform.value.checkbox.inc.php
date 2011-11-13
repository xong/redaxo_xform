<?php

class rex_xform_value_checkbox extends rex_xform_value_abstract
{

  function enterObject()
  {
    if ($this->getElement(3) == "") $this->setElement(3,1);

    $checked = "";

    if($this->getValue() == $this->getElement(3))
    {
      $checked = ' checked="checked"';
      $v = $this->getElement(3);
    }
    elseif($this->getValue() == 1)
    {
      $checked = ' checked="checked"';
      $v = 1;
    }
    else
    {
      $this->setValue("0");
      $v = 1;
    }
    
    $disabled = "";
	if($this->getElement("disabled")) {
		$disabled = ' disabled="disabled"';
	}
    
    
		$class = $this->getHTMLClass();
		$classes = $class;
    
    if (isset($this->params["warning"][$this->getId()]))
    {
      $classes .= $this->params["warning"][$this->getId()];
    }
    
		$classes = (trim($classes) != '') ? ' class="'.trim($classes).'"' : '';

    $before = '';
    $after = '';
    $label = ($this->getElement(2) != '') ? '<label'.$classes.' for="'.$this->getFieldId().'" >'.rex_i18n::translate($this->getElement(2)).'</label>' : '';
    $field = '<input'.$classes.' id="'.$this->getFieldId().'" type="checkbox" name="'.$this->getFieldName().'" value="'.$v.'" '.$checked.$disabled.' />';
		$extra = '';
    $html_id = $this->getHTMLId();
    $name = $this->getName();

    
		$f = new rex_fragment();
		$f->setVar('before', $before, false);
		$f->setVar('after', $after, false);
		$f->setVar('label', $label, false);
		$f->setVar('field', $field, false);
		$f->setVar('extra', $extra, false);
		$f->setVar('html_id', $html_id, false);
		$f->setVar('name', $name, false);
		$f->setVar('class', $class, false);
		
		$fragment = $this->params['fragment'];
		$this->params["form_output"][$this->getId()] = $f->parse($fragment);


    $this->params["value_pool"]["email"][$this->getElement(1)] = stripslashes($this->getValue());
    if ($this->getElement(5) != "no_db") { $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue(); }

  }

  function getDescription()
  {
    return "checkbox -> Beispiel: checkbox|check_design|Bezeichnung|Value|1/0|[no_db]|extras";
  }

  function getDefinitions()
  {

    return array(
            'type' => 'value',
            'name' => 'checkbox',
            'values' => array(
                array( 'type' => 'name',   'label' => 'Name' ),
                array( 'type' => 'text',    'label' => 'Bezeichnung'),
                array( 'type' => 'text',    'label' => 'Wert wenn angeklickt', 'default' => 1),
                array( 'type' => 'boolean', 'label' => 'Defaultstatus',         'default' => 1),
                array( 'type' => 'no_db',   'label' => 'Datenbank',  'default' => 1),
              ),
            'description' => 'Ein Selectfeld mit festen Definitionen.',
            'dbtype' => 'varchar(255)',
            'famous' => TRUE
    );

  }



}

?>