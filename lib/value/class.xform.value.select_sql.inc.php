<?php

class rex_xform_value_select_sql extends rex_xform_value_abstract
{

	function enterObject()
	{

		$multiple = false;
		if((int)$this->getElement(8) == 1)
			$multiple = true; 

		$size = (int) $this->getElement(9);
		if($size < 1)
			$size = 1; 

		$SEL = new rex_select();
		$SEL->setId($this->getFieldId().'-s');
		
		if($multiple)
		{
			if($size == 1)
			{
				$size = 5;
			}
			$SEL->setName($this->getFieldName().'[]');
			$SEL->setSize($size);
			$SEL->setMultiple(1);
		}
		else
		{
			$SEL->setName($this->getFieldName());
			$SEL->setSize(1);
		}
		


		$sql = rex_sql::factory();
		//$sql->debugsql = $this->params['debug'];
		$sql->setQuery($this->getElement(3));


		// mit --- keine auswahl ---
		if (!$multiple && $this->getElement(6) == 1)
			$SEL->addOption($this->getElement(7), '0');
    

    $results = $sql->getArray();
    
		$sqlnames = array();
		foreach($results as $result)
		{
			$v = $result['name'];
			$k = $result['id'];
			$SEL->addOption($v, $k);
			
			$sqlnames[$k] = $v;
		}
		
		

		if (!$this->params['send'] && $this->getValue() == '' && $this->getElement(4) != '')
		{
			$this->setValue($this->getElement(4));
		}

		if(!is_array($this->getValue()))
		{
			$this->setValue(explode(',', $this->getValue()));
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
		

		$this->params["value_pool"]["email"][$this->getElement(1)] = stripslashes($this->getValue());
		if ($this->getElement(5) != "no_db") 
			$this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();
		
	}
	
	function getDescription()
	{
		return "select_sql -> Beispiel: select_sql|label|Bezeichnung:|select id,name from table order by name|[defaultvalue]|[no_db]|1/0 Leeroption|Leeroptionstext|1/0 Multiple Feld";
	}
	
	function getDefinitions()
	{
		return array(
						'type' => 'value',
						'name' => 'select_sql',
						'values' => array(
							array( 'type' => 'name',		'label' => 'Name' ),
							array( 'type' => 'text',		'label' => 'Bezeichnung'),
							array( 'type' => 'text',		'label' => 'Query mit "select id, name from .."'),
					   		array( 'type' => 'text',		'label' => 'Defaultwert (opt.)'),
					   		array( 'type' => 'no_db',   	'label' => 'Datenbank',  'default' => 1),
					   		array( 'type' => 'boolean',		'label' => 'Leeroption'),
					   		array( 'type' => 'text',		'label' => 'Text bei Leeroption (Bitte auswählen)'),
					   		array( 'type' => 'boolean',		'label' => 'Mehrere Felder möglich'),
					   		array( 'type' => 'text',		'label' => 'Höhe der Auswahlbox'),
						),
						'description' => 'Hiermit kann man SQL Abfragen als Selectbox nutzen',
						'dbtype' => 'text'
					);
	}
	
}

?>