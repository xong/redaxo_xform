<?php

class rex_xform_value_submit_image extends rex_xform_value_abstract
{

	function preAction()
	{
		$this->params["submit_btn_show"] = FALSE; // ist referenz auf alle parameter.
	}
	

  function enterObject()
  {
    $this->setValue($this->getElement(2));
    $src = $this->getElement(3);

		$class = $this->getHTMLClass();
		$classes = $class;

		$classes = (trim($classes) != '') ? ' class="'.trim($classes).'"' : '';
				
    $before = '';
    $after = '';
		$label = '';
		$field = '<input'.$classes.' id="'.$this->getFieldId().'" type="image" src="'.$src.'" name="'.$this->getFieldName().'" value="'.htmlspecialchars(stripslashes($this->getValue())).'" />';
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
    if ($this->getElement(4) != "no_db") $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();
  }

  function getDescription()
  {
    return "submitimage -> Beispiel: submitimage|label|value|imgsrc|[no_db]";
  }
}

?>