<?php

class rex_xform_submitimage extends rex_xform_abstract
{

	function loadParams($params = array(),$elements = array())
	{
		$params["submit_btn_show"] = FALSE;
		$this->params = $params;
		$this->elements = $elements;
	}

	function enterObject()
	{	
		$this->setValue($this->getElement(2));
		$src = $this->getElement(3);
	
       	$this->params["form_output"][] = '
			<p class="formsubmit formlabel-'.$this->getName().'">
				<input type="image" src="'.$src.'" class="submit " name="FORM[' . 
				$this->params["form_name"] . '][el_' . $this->getId() . ']" id="el_' . $this->getId() . '" value="' . 
				htmlspecialchars(stripslashes($this->getValue())) . '" />
			</p>';
		$this->params["value_pool"]["email"][$this->getElement(1)] = stripslashes($this->getValue());
		if ($this->getElement(4) != "no_db") $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();
	}
	
	function getDescription()
	{
		return "submitimage -> Beispiel: submitimage|label|value|imgsrc|[no_db]";
	}
}

?>