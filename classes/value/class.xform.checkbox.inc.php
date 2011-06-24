<?php

class rex_xform_checkbox extends rex_xform_abstract
{

	function enterObject(&$email_elements,&$sql_elements,&$warning,&$form_output,$send = 0)
	{
		if (!isset($this->elements[3]) || $this->elements[3]=="") $this->elements[3] = 1;

		$checked = "";

		if($this->getElement(3) != "" && $this->getValue() == $this->getElement(3)) {
			$checked = ' checked="checked"';
			$v = $this->getElement(3);
		
		}elseif($this->getValue() == 1) {
			$checked = ' checked="checked"';
			$v = 1;
			
		}else {
			$this->setValue("0");
			$v = 1;
		}

		$wc = "";
		if (isset($warning[$this->getId()])) {
			$wc = $warning[$this->getId()];
		}

		$form_output[$this->getId()] = '
			<p class="formcheckbox formlabel-'.$this->getName().'" id="'.$this->getHTMLId().'">
				<input type="checkbox" class="checkbox '.$wc.'" name="'.$this->getFieldName().'" id="'.$this->getFieldId().'" value="'.$v.'" '.$checked.' />
				<label class="checkbox '.$wc.'" for="'.$this->getFieldId().'" >'.$this->elements[2].'</label>
			</p>';

		$email_elements[$this->elements[1]] = stripslashes($this->value);
		if (!isset($this->elements[5]) || $this->elements[5] != "no_db") {
			$sql_elements[$this->elements[1]] = $this->value;
		}

	}

	function getDescription()
	{
		return "checkbox -> Beispiel: checkbox|check_design|Bezeichnung|Value|1/0|[no_db]";
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