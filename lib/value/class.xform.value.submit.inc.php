<?php

class rex_xform_value_submit extends rex_xform_value_abstract
{

	function enterObject()
	{	
		
		if(!$this->params["submit_btn_show"]) {
			return;	
		}
		
		$this->setValue($this->getElement(2));

		$class = $this->getHTMLClass();
		$classes = $class;
		
		if (trim($this->getElement(4)) != '')
		{
		  $classes .= ' '.trim($this->getElement(4));
		}

		if (isset($this->params["warning"][$this->getId()]))
		{
		  $classes .= ' '.$this->params["warning"][$this->getId()];
    }
		
		$classes = (trim($classes) != '') ? ' class="'.trim($classes).'"' : '';

	$value = htmlspecialchars(stripslashes($this->getValue()));
	if($value == "") {
		$value = $this->params["submit_btn_label"];
	}


		
    $before = '';
    $after = '';
		$label = '';
		$field = '<input'.$classes.' id="'.$this->getFieldId().'" type="submit" name="'.$this->getFieldName().'" value="'.$value.'" />';
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
		if ($this->getElement(3) != "no_db")
		{
		  $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();
		}
		
		$this->params["submit_btn_show"] = FALSE;
	}
	
	function getDescription()
	{
		return "submit -> Beispiel: submit|label|value|[no_db]|cssclassname";
	}
}

?>