<?php

class rex_xform_resetbutton extends rex_xform_abstract
{

	function enterObject()
	{
		$this->setValue($this->getElement(2));

		$css_class = "";
		if ($this->getElement(4) != "")
		{
			$css_class = $this->getElement(3);
		}
		$wc = $css_class;

		$this->params["form_output"][] = '
				<p class="formsubmit formlabel-'.$this->getName().'" id="'.$this->getHTMLId().'">
				<label class="text ' . $wc . '" for="el_' . $this->getId() . '" >&nbsp;</label>
				<input type="reset" class="submit ' . $wc . '" id="el_' . $this->getId() . '" value="' . 
		htmlspecialchars(stripslashes($this->getValue())) . '" />
				</p>';

	}

	function getDescription()
	{
		return "submit -> Beispiel: submit|label|value|cssclassname";
	}
}

?>