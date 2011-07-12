<?php

class rex_xform_mailto extends rex_xform_abstract
{

	function enterObject()
	{
	
		// mailto als referenz auf anderes input feld (muss vor dem mailto feld stehen!)
		if(isset($this->params["value_pool"]["email"][$this->getElement(1)]))
		{
			$this->params["mail_to"] = $this->params["value_pool"]["email"][$this->getElement(1)];
			$this->params["mail_to"] = str_replace(array("\n", "\r\n", "\r"), '', $this->params["mail_to"]);

		}else
		{
			// direkt angegebene Emailadresse
			$this->params["mail_to"] = $this->getElement(1);
		}
	
	}
	
	function getDescription()
	{
		return "	mailto -> Beispiel: mailto|email@domain.de 
			<br />	mailto -> Beispiel:mailto|usr_email (Verweis auf vorhergendes Eingabefeld)";
	}
}

?>