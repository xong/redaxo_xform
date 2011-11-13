<?php

class rex_xform_value_select extends rex_xform_value_abstract
{

	function enterObject()
	{

		$multiple = FALSE;
		if($this->getElement(6) == 1)
		  $multiple = TRUE;

		$size = (int) $this->getElement(7);
		if($size < 1)
		  $size = 1;

		$SEL = new rex_select();
		$SEL->setId($this->getFieldId());
		
		if($multiple)
		{
			if($size == 1)
			{
				$size = 5;
			}
			$SEL->setName($this->getFieldName()."[]");
			$SEL->setSize($size);
			$SEL->setMultiple(1);
		}
		else
		{
			$SEL->setName($this->getFieldName());
			$SEL->setSize(1);
		}
    
    $options = explode(',', $this->getElement(3));
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
			$SEL->addOption(rex_i18n::translate($value), $text);
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

		
    $before = '';
    $after = '';
		$label = ($this->getElement(2) != '') ? '<label'.$classes.' for="' . $this->getFieldId() . '">' . rex_i18n::translate($this->getElement(2)) . '</label>' : '';
		$field = $SEL->get();
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
		
		

		$this->params["value_pool"]["email"][$this->getElement(1)] = $this->getValue();
		if ($this->getElement(4) != "no_db") $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();

	}

	function getDescription()
	{
		return "select -> Beispiel: select|gender|Geschlecht *|Frau=w,Herr=m|[no_db]|defaultwert|multiple=1";
	}

	function getDefinitions()
	{
		return array(
            'type' => 'value',
            'name' => 'select',
            'values' => array(
				array( 'type' => 'name',   'label' => 'Feld' ),
				array( 'type' => 'text',    'label' => 'Bezeichnung'),
				array( 'type' => 'text',    'label' => 'Selektdefinition, kommasepariert',   'example' => 'w=Frau,m=Herr'),
				array( 'type' => 'no_db',   'label' => 'Datenbank',          'default' => 1),
				array( 'type' => 'text',    'label' => 'Defaultwert'),
				array( 'type' => 'boolean', 'label' => 'Mehrere Felder möglich'),
				array( 'type' => 'text',    'label' => 'Höhe der Auswahlbox'),
				),
            'description' => 'Ein Selektfeld mit festen Definitionen',
            'dbtype' => 'text'
            );

	}

}

?>