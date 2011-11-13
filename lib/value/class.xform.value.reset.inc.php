<?php

class rex_xform_value_reset extends rex_xform_value_abstract
{

	function enterObject()
	{
		$this->setValue($this->getElement(2));
		
		
		$class = $this->getHTMLClass();
		$classes = $class;
		
		if ($this->getElement(3) != '')
		{
  		$classes .= ' '.$this->getElement(3);
		}
		
		
		$classes = (trim($classes) != '') ? ' class="'.trim($classes).'"' : '';

		
    $before = '';
    $after = '';
		$label = '';
		$field = '<input'.$classes.' id="'.$this->getFieldId().'" type="reset" name="'.$this->getFieldName().'" value="'.htmlspecialchars(stripslashes($this->getValue())).'" />';
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
		
	}

	function getDescription()
	{
		return "reset -> Beispiel: reset|label|value|cssclassname";
	}
}

?>