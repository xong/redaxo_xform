<?php

// Dateiname: class.xform.radio.inc.php

class rex_xform_value_radio extends rex_xform_value_abstract
{
	
	function enterObject()
	{
    
		$SEL = new rex_radio();
		$SEL->setId($this->getHTMLId());	
		$SEL->setName($this->getFieldName());
		
		
    $options = explode(',', $this->getElement(3));
    if (is_array($options) && count($options) > 0)
    {
      foreach ($options as $option)
      {
        $params = explode('=', $option);
        $value = $params[0];
        if (isset ($params[1]))
        {
          $text = $params[1];
        }
        else
        {
          $text = $params[0];
        }
        
  			$SEL->addOption($value, $text);
      }
    }

		if (!$this->params['send'] && $this->getValue() == '' && $this->getElement(5) != '')
		{
			$this->setValue($this->getElement(5));
		}

		if(!is_array($this->getValue()))
		{
			$this->setValue(explode(",",$this->getValue()));
		}

		foreach($this->getValue() as $v)
		{
			$SEL->setSelected($v);
		}
		
		
		$this->setValue(implode(",",$this->getValue()));



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


		$this->params["value_pool"]["email"][$this->getElement(1)] = stripslashes($this->getValue());
		if ($this->getElement(4) != "no_db") 
			$this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();

	}
	
	function getDescription()
	{
		return "radio -> Beispiel: radio|gender|Geschlecht *|Frau=w,Herr=m|[no_db]|defaultwert|[fieldset]";
	}
}