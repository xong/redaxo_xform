<?php

// Dateiname: class.xform.radio.inc.php

class rex_xform_radio extends rex_xform_abstract
{
	
	function enterObject(&$email_elements,&$sql_elements,&$warning,&$form_output,$send = 0)
	{

		$SEL = new rex_radio();
		$SEL->setId($this->getHTMLId());
		
		$SEL->setName($this->getFormFieldname());


		$options = explode(";",$this->elements[3]);

		foreach($options as $option)
		{
			$t = explode("=",$option);
			$v = $t[0];
			$k = $t[1];
			$SEL->addOption($v, $k);
			$sqlnames[$k] = $t[0];
		}

		$wc = "";
		if (isset($warning[$this->getId()])) 
			$wc = $warning[$this->getId()];

		$SEL->setStyle(' class="select ' . $wc . '"');

		if ($this->value=="" && isset($this->elements[4]) && $this->elements[4] != "") 
			$this->value = $this->elements[4];

		if(!is_array($this->value))
		{
			$this->value = explode(",",$this->value);
		}

		foreach($this->value as $v)
		{
			$SEL->setSelected($v);
		}
		
		$form_output[] = '
			<p class="formradio formlabel-'.$this->getName().'"  id="'.$this->getHTMLId().'">
				<label class="radio ' . $wc . '" for="' . $this->getHTMLId() . '" >' . $this->elements[2] . '</label>
			</p>'.$SEL->get();

		/*
		if (isset($sqlnames[$this->value])) 
			$email_elements[$this->elements[1].'_SQLNAME'] = stripslashes($sqlnames[$this->value]);
		*/

		$this->value = implode(",",$this->value);

		$email_elements[$this->elements[1]] = stripslashes($this->value);
		if (!isset($this->elements[5]) || $this->elements[5] != "no_db") 
			$sql_elements[$this->elements[1]] = $this->value;

	}
	
	function getDescription()
	{
		return "radio -> Beispiel: radio|gender|Geschlecht *|Frau=w;Herr=m|[no_db]|defaultwert";
	}
}