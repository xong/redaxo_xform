<?php

class rex_xform_value_week extends rex_xform_value_abstract
{

	function enterObject()
	{

		if ($this->getValue() == '' && !$this->params['send'])
		{
			$this->setValue($this->getElement(3));
		}


		$class = $this->getHTMLClass();
		$classes = $class;
		
		if ($this->getElement(5) != '')
		{
  		$classes .= ' '.$this->getElement(5);
    }
		
		if (isset($this->params["warning"][$this->getId()]))
		{
			$classes .= ' '.$this->params["warning"][$this->getId()];
		}
		
		$classes = (trim($classes) != '') ? ' class="'.trim($classes).'"' : '';
    
    
    $attributes = '';
		if ($this->getElement(6) != '')
		{
  		$attributes .= ' min="'.$this->getElement(6).'"';
    }
		if ($this->getElement(7) != '')
		{
  		$attributes .= ' max="'.$this->getElement(7).'"';
    }
		if ((int)$this->getElement(8) > 0)
		{
  		$attributes .= ' step="'.(int)$this->getElement(8).'"';
    }
    
    
		
    $before = '';
    $after = '';    
    $label = ($this->getElement(2) != '') ? '<label'.$classes.' for="' . $this->getFieldId() . '">' . rex_i18n::translate($this->getElement(2)) . '</label>' : '';	
		$field = '<input'.$classes.' id="'.$this->getFieldId().'" type="week" name="'.$this->getFieldName().'" value="'.htmlspecialchars(stripslashes($this->getValue())).'"'.$attributes.' />';
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
		{
			$this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();
		}
	}

	function getDescription()
	{
		return "text -> Beispiel: week|label|Bezeichnung|defaultwert|[no_db]|classes|min|max|step";
	}

	function getDefinitions()
	{
		return array(
						'type' => 'value',
						'name' => 'week',
						'values' => array(
									array( 'type' => 'name',   'label' => 'Woche' ),
									array( 'type' => 'text',    'label' => 'Bezeichnung'),
									array( 'type' => 'text',    'label' => 'Defaultwert'),
									array( 'type' => 'no_db',   'label' => 'Datenbank',  'default' => 1),
									array( 'type' => 'text',    'label' => 'classes'),
									array( 'type' => 'text',    'label' => 'Mindestwert'),
									array( 'type' => 'text',    'label' => 'Maximalwert'),
									array( 'type' => 'text',    'label' => 'Schritte'),
								),
						'description' => 'Woche',
						'dbtype' => 'text',
						'famous' => TRUE
						);

	}
}

?>