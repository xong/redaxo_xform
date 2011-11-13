<?php

class rex_xform_value_radio_sql extends rex_xform_value_abstract
{

	function enterObject()
	{
    
		$SEL = new rex_radio();
		$SEL->setId($this->getHTMLId());	
		$SEL->setName($this->getFieldName());
		

		$query = $this->getElement(3);

		$sql = rex_sql::factory();
		$sql->debugsql = $this->params["debug"];
		$sql->setQuery($query);

    $fields = $sql->getFieldnames();
    $options = $sql->getArray();
    
    if (is_array($fields) && count($fields) == 2)
    {
      foreach($options as $option)
      {
        $k = $option[$fields['0']];
        $v = $option[$fields['1']];
        $SEL->addOption($v, $k);
      }
    }


		if ($this->getElement(4) != "")
		{
		  $this->setValue($this->getElement(4));
		}

		if(!is_array($this->getValue()))
		{
			$this->setValue(explode(",", $this->getValue()));
		}

		foreach($this->getValue() as $v)
		{
			$SEL->setSelected($v);
		}



		$class = $this->getHTMLClass();
		$classes = $class;
		
		if (isset($this->params['warning'][$this->getId()]))
		{
			$classes .= ' '.$this->params['warning'][$this->getId()];
		}
		
		$classes = (trim($classes) != '') ? ' class="'.trim($classes).'"' : '';
		
		
		$SEL->setStyle($classes);
		
		
		$label = '';
		$field = '';

		if ($this->getElement(6) == 'fieldset')
		{
		  $field .= '<fieldset>';
		  $field .= ($this->getElement(2) != '') ? '<legend'.$classes.'>' . rex_i18n::translate($this->getElement(2)) . '</legend>' : '';
  		$field .= $SEL->get();
		  $field .= '</fieldset>';
		}
		else
		{
  		$label .= ($this->getElement(2) != '') ? '<label'.$classes.'>' . rex_i18n::translate($this->getElement(2)) . '</label>' : '';
		  $field .= $SEL->get();
		}
		
		
    $before = '';
    $after = '';
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
		
		

		$this->setValue(implode(",",$this->getValue()));

		$this->params["value_pool"]["email"][$this->getElement(1)] = stripslashes($this->getValue());
		if ($this->getElement(5) != "no_db") $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();
		
	}
	
	function getDescription()
	{
		return "radio_sql -> Beispiel: select_sql|label|Bezeichnung:|select id,name from table order by name|[defaultvalue]|[no_db]|[fieldset]";
	}
	

	
}

?>