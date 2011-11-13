<?php

class rex_xform_value_hidden extends rex_xform_value_abstract
{

	function enterObject()
	{
		
		if ($this->getElement(3) == 'REQUEST' && isset($_REQUEST[$this->getName()]))
		{
			$this->setValue(stripslashes(rex_request($this->getName())));
		}
		else
		{
			$this->setValue($this->getElement(2));
		}
		
		$this->params["form_output"][$this->getId()] = '<input id="'.$this->getHTMLId().'" type="hidden" name="'.$this->getName().'" value="'.htmlspecialchars($this->getValue()).'" />';


		$this->params["value_pool"]["email"][$this->getName()] = stripslashes($this->getValue());
		if ($this->getElement(4) != "no_db") 
			$this->params["value_pool"]["sql"][$this->getName()] = $this->getValue();
	}
	
	function getDescription()
	{
		return "
				hidden -> Beispiel: hidden|status|default_value||[no_db]<br />	hidden -> Beispiel: hidden|job_id|default_value|REQUEST|[no_db]";
	}

	function getLongDescription()
	{
		return '
		Hiermit können Werte fest als Wert zum Formular eingetragen werden z.B. 
		
		hidden|status|abgeschickt
		
		Dieser Wert kann wie alle anderen Werte übernommen und in der Datenbank gepeichert, oder auch
		im E-Mail Formular anzeigt werden.
		
		Weiterhin gibt es mit "REQUEST" auch die Mglichkeit, Werte auf der Url oder einem
		vorherigen Formular zu übernehmen.
		
		hidden -> Beispiel: hidden|job_id|default_value|REQUEST|
		
		Hier wird die job_id übernommen und direkt wieder über das Formular mitversendet.
		
		mit "no_db" wird definiert, dass bei einer eventuellen Datenbankspeicherung, dieser
		Wert nicht übernommen wird.
		';	
	}

}