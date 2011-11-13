<?php

class rex_xform_value_textarea_clang extends rex_xform_value_abstract
{

	function getLangDivider()
	{
		return "^^^^°°°°";
	}

	function enterObject()
	{

		$text = array();
		if(is_array($this->getValue()))
		{
			foreach($this->getValue() as $k => $t)
			{
				$text[$k] = $t;
      }
    }
    elseif(is_string($this->getValue()) and $this->getValue() != '')
    {
			$text = explode(self::getLangDivider(),$this->getValue());
    }



		$class = $this->getHTMLClass();
		$classes = $class;
		
		if (isset($this->params['warning'][$this->getId()]))
		{
			$classes .= ' '.$this->params['warning'][$this->getId()];
		}
		
		$classes = (trim($classes) != '') ? ' class="'.trim($classes).'"' : '';
		
		

		$clangs = rex_clang::getAll();
		$cur_clang = rex_clang::getId();

		$navi = '';
		$fields = '';
		foreach($clangs as $l => $lang)
		{
			$navi .= '<li><a id="'.$class.'-navi-'.$this->getFieldId($l).'" href="#'.$this->getFieldId($l).'">'.$lang.'</a></li>';
			
			$t = '';
			if(isset($text[$l]))
				$t = $text[$l];
			
			$fields .= '<textarea'.$classes.' name="'.$this->getFieldName($l).'" id="'.$this->getFieldId($l).'" cols="1" rows="1">'.htmlspecialchars(stripslashes($t)).'</textarea>';
			
		}
		
		
		$before = ($navi != '') ? '<ul class="'.$class.'-navi">'.$navi.'</ul>' : '';

		$after = '
			<script type="text/javascript">
			jQuery(function($) {
			        var tabContainers = $(\'textarea.'.$class.'\');
			        
			        $(\'.'.$class.'-navi a\').click(function () {
			        
			                tabContainers.hide().filter(this.hash).show();
			                $(\'.'.$class.'-navi a\').removeClass(\'active\');
			                $(this).addClass(\'active\');
			                return false;

			        }).filter(\'#'.$class.'-navi-'.$this->getFieldId($cur_clang).'\').click();
			        
			});
			</script>';
			
			

		$label = ($this->getElement(2) != '') ? '<label'.$classes.' for="' . $this->getFieldId() . '">' . rex_i18n::translate($this->getElement(2)) . '</label>' : '';
		$field = $fields;
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
		
		

		$this->setValue(implode($this->getLangDivider(),$text));

		$this->params["value_pool"]["email"][$this->getElement(1)] = stripslashes($this->getValue());
		if ($this->getElement(3) != "no_db") 
			$this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();
	}
	
	function getDescription()
	{
		return "textarea -> Beispiel: lang_textarea|label|FieldLabel|[no_db]";
	}
	
	function getDefinitions()
	{
    return array(
            'type' => 'value',
            'name' => 'lang_textarea',
            'values' => array(
              array( 'type' => 'name',   'label' => 'Feld' ),
              array( 'type' => 'text',    'label' => 'Bezeichnung'),
              array( 'type' => 'no_db',   'label' => 'Datenbank',  'default' => 1),
            ),
            'description' => 'Ein mehrzeiliges mehrsprachiges Textfeld als Eingabe',
            'dbtype' => 'text'
      );
	}
}

?>