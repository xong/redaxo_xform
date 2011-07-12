<?php

class rex_xform_submit extends rex_xform_abstract
{

	function preAction()
	{
		$this->params["submit_btn_show"] = FALSE; // ist referenz auf alle parameter.
	}

	function enterObject()
	{	
		$this->setValue($this->getElement(2));

		$css_class = "";
		if ($this->getElement(4) != "") $css_class = $this->getElement(4);
	
		$wc = $css_class;
		if (isset($this->params["warning"][$this->getId()])) $wc = $this->params["warning"][$this->getId()]." ";
	
       	$this->params["form_output"][] = '
				<p class="formsubmit formlabel-'.$this->getName().'">
				<input type="submit" class="submit ' . $wc . '" name="FORM['.$this->params["form_name"] . '][el_' . $this->getId() . ']" id="el_' . $this->getId() . '" value="' . 
				htmlspecialchars(stripslashes($this->getValue())) . '" />
				</p>';
		$this->params["value_pool"]["email"][$this->getElement(1)] = stripslashes($this->getValue());
		if ($this->getElement(3) != "no_db") $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();
		
		$this->params["submit_btn_show"] = FALSE;
	}
	
	function getDescription()
	{
		return "submit -> Beispiel: submit|label|value|[no_db]|cssclassname";
	}
}

?>