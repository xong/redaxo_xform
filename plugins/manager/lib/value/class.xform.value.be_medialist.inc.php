<?php

class rex_xform_value_be_medialist extends rex_xform_value_abstract
{

	function enterObject()
	{	
		
		global $I18N;
		
		static $tmp_medialist=0;
		
		// if (!isset($tmp_medialist)) $tmp_medialist = 0;
		$tmp_medialist++;
		
		$options = '';
		$medialistarray = explode(",",$this->getValue());
		if (is_array($medialistarray))
		{
			for($j=0;$j<count($medialistarray);$j++)
			{
				if (current($medialistarray)!="")
					$options .= "<option value='".current($medialistarray)."'>".current($medialistarray)."</option>\n";
				next($medialistarray);
			}
		}


		$class = $this->getHTMLClass();
		$classes = $class;
		if (isset($this->params["warning"][$this->getId()]))
		{
			$classes .= ' '.$this->params["warning"][$this->getId()];
		}
		
		$classes = (trim($classes) != '') ? ' class="'.trim($classes).'"' : '';

		
		
    $before = '';
    $after = '';    
    $label = ($this->getElement(2) != '') ? '<label'.$classes.' for="' . $this->getFieldId() . '">' . rex_i18n::translate($this->getElement(2)) . '</label>' : '';	
		$field = '
      <div class="rex-widget">
        <div class="rex-widget-medialist">
          <input type="hidden" name="'.$this->getFieldName().'" id="REX_MEDIALIST_'.$tmp_medialist.'" value="'.htmlspecialchars(stripslashes($this->getValue())) . '" />
          <p class="rex-widget-field">
            <select'.$classes.' name="MEDIALIST_SELECT['.$tmp_medialist.']" id="REX_MEDIALIST_SELECT_'.$tmp_medialist.'" multiple="multiple">
            ' . $options . '
            </select>
          </p>
  
          <p class="rex-widget-icons rex-widget-2col">
            <span class="rex-widget-column rex-widget-column-first">
              <a href="#" class="rex-icon-file-top" onclick="moveREXMedialist('.$tmp_medialist.',\'top\');return false;" title="'. rex_i18n::translate('var_medialist_move_top') .'"></a>
              <a href="#" class="rex-icon-file-up" onclick="moveREXMedialist('.$tmp_medialist.',\'up\');return false;" title="'. rex_i18n::translate('var_medialist_move_up') .'"></a>
              <a href="#" class="rex-icon-file-down" onclick="moveREXMedialist('.$tmp_medialist.',\'down\');return false;" title="'. rex_i18n::translate('var_medialist_move_down') .'"></a>
              <a href="#" class="rex-icon-file-bottom" onclick="moveREXMedialist('.$tmp_medialist.',\'bottom\');return false;" title="'. rex_i18n::translate('var_medialist_move_bottom') .'"></a>
            </span>
            <span class="rex-widget-column">
              <a href="#" class="rex-icon-file-open" onclick="openREXMedialist('.$tmp_medialist.');return false;" title="'. rex_i18n::translate('var_media_open') .'"></a>
              <a href="#" class="rex-icon-file-add" onclick="addREXMedialist('.$tmp_medialist.');return false;" title="'. rex_i18n::translate('var_media_new') .'"></a>
              <a href="#" class="rex-icon-file-delete" onclick="deleteREXMedialist('.$tmp_medialist.');return false;" title="'. rex_i18n::translate('var_media_remove') .'"></a>
              <a href="#" class="rex-icon-file-view" onclick="viewREXMedialist('.$tmp_medialist.');return false;" title="'. rex_i18n::translate('var_media_open') .'"></a>
            </span>
          </p>
          <div class="rex-media-preview"></div>
        </div>
      </div>';
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
		if ($this->getElement(3) != "no_db") $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();

	}
	
	function getDescription()
	{
		return "be_medialist -> Beispiel: be_medialist|label|Bezeichnung|no_db";
	}
	
  function getDefinitions()
  {
    return array(
            'type' => 'value',
            'name' => 'be_medialist',
            'values' => array(
              array( 'type' => 'name',   'label' => 'Name' ),
              array( 'type' => 'text',   'label' => 'Bezeichnung'),
            ),
            'description' => 'Medialiste, welches Dateien aus dem Medienpool holt',
            'dbtype' => 'text'
      );
  }
	
	
	
}

?>