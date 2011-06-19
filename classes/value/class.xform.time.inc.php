<?php

class rex_xform_time extends rex_xform_abstract
{

	function enterObject(&$email_elements,&$sql_elements,&$warning,&$form_output,$send = 0)
	{

		$hour = "";
		$min = "";

		if (!is_array($this->getValue()) && (strlen($this->getValue()) == 2 || strlen($this->getValue()) == 4))
		{
			$hour = (int) substr($this->value,0,2);
			$min = (int) substr($this->value,2,2);
		}else
		{
			if (isset($_REQUEST["FORM"][$this->params["form_name"]]['el_'.$this->id]["hour"])) $hour = $_REQUEST["FORM"][$this->params["form_name"]]['el_'.$this->id]["hour"];
			if (isset($_REQUEST["FORM"][$this->params["form_name"]]['el_'.$this->id]["min"])) $min = $_REQUEST["FORM"][$this->params["form_name"]]['el_'.$this->id]["min"];
			if($hour != "") { $hour = (int) $hour; $hour = substr($hour,0,2); $hour = str_pad($hour, 2, "0", STR_PAD_LEFT); }
			if($min != "") { $min = (int) $min; $min = substr($min,0,2); $min = str_pad($min, 2, "0", STR_PAD_LEFT); }
		}
		
		$formname = $this->getFormFieldname();

		if($hour != "")
		{
			$email_elements[$this->getName()] = "$hour$min";
			$sql_elements[$this->getName()] = "$hour$min";
		}
		
		$out = "";
		$out .= '
		<p class="formtime formlabel-'.$this->getName().'" id="'.$this->getHTMLId().'">
			<label class="select" for="el_'.$this->getId().'" >'.$this->elements[2].'</label>';
					
		$hsel = new rex_select;
		$hsel->setName($formname.'[hour]');
		$hsel->setStyle("width:55px;");
		$hsel->setId('el_'.$this->id.'_hour');
		$hsel->setSize(1);
		$hsel->addOption("HH","");
		$hsel->addOption("01","01");
		$hsel->addOption("02","02");
		$hsel->addOption("03","03");
		$hsel->addOption("04","04");
		$hsel->addOption("05","05");
		$hsel->addOption("06","06");
		$hsel->addOption("07","07");
		$hsel->addOption("08","08");
		$hsel->addOption("09","09");
		$hsel->addOption("10","10");
		$hsel->addOption("11","11");
		$hsel->addOption("12","12");
		$hsel->addOption("13","13");
		$hsel->addOption("14","14");
		$hsel->addOption("15","15");
		$hsel->addOption("16","16");
		$hsel->addOption("17","17");
		$hsel->addOption("18","18");
		$hsel->addOption("19","19");
		$hsel->addOption("20","20");
		$hsel->addOption("21","21");
		$hsel->addOption("22","22");
		$hsel->addOption("23","23");
		$hsel->addOption("24","24");
		$hsel->setSelected($hour);
		$out .= $hsel->get();

		$msel = new rex_select;
		$msel->setName($formname.'[min]');
		$msel->setStyle("width:55px;");
		$msel->setId('el_'.$this->id.'_min');
		$msel->setSize(1);
		$msel->addOption("MIN","");
		$msel->addOption("00","00");
		$msel->addOption("15","15");
		$msel->addOption("30","30");
		$msel->addOption("45","45");
		$msel->setSelected($min);
		$out .= $msel->get();

		$out .= '</p>';

		$form_output[] = $out;

	}
	function getDescription()
	{
		return "date -> Beispiel: date|feldname|Text *|jahrstart|jahrend|[format: Y-m-d]";
	}
	
	function getDefinitions()
	{
		return array(
					'type' => 'value',
					'name' => 'time',
					'values' => array(
								array( 'type' => 'name',   'label' => 'Feld' ),
								array( 'type' => 'text',    'label' => 'Bezeichnung'),
							),
					'description' => 'Uhrzeitfeld Eingabe',
					'dbtype' => 'text'
					);

	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}

?>