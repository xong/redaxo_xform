<?php

class rex_xform_showvalue extends rex_xform_abstract
{

	function enterObject()
	{

		if ($this->getValue() == "" && !$this->params["send"])
		{
			$this->setValue($this->getElement(3));
		}

		// hidden muss drin sein, da bei disabled felder die werte nicht �bertragen werden

		$this->params["form_output"][] = '
			<p class="formtext"  id="'.$this->getHTMLId().'">
			<label class="text" for="el_'.$this->getId().'">'.$this->getElement(2).'</label>
			<input type="hidden" name="FORM['.$this->params["form_name"].'][el_'.$this->getId().']" value="'.htmlspecialchars(stripslashes($this->getValue())).'" />
			<input type="text" class="inp_disabled" disabled="disabled" id="el_'.$this->getId().'" value="'.htmlspecialchars(stripslashes($this->getValue())).'" />
			</p>';

		$this->params["value_pool"]["email"][$this->getElement(1)] = stripslashes($this->getValue());
		// if ($this->getElement(4) != "no_db") $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();
	}
	
	function getDescription()
	{
		return "showvalue -> Beispiel: showvalue|login|Loginname|defaultwert";
	}
}

?>