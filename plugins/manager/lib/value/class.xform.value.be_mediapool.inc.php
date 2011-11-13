<?php

class rex_xform_value_be_mediapool extends rex_xform_value_abstract
{

	function enterObject()
	{	
		global $REX;
		
		if(!isset($REX["xform_classes_be_mediapool"]))
			$REX["xform_classes_be_mediapool"] = 0;
		
		$REX["xform_classes_be_mediapool"]++;
		
		$i = $REX["xform_classes_be_mediapool"];
		
		if ($this->getValue() == "" && !$this->params["send"])
			$this->setValue($this->getElement(3));


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
		      <div class="rex-widget-media">
		        <p class="rex-widget-field">
		          <input type="text"'.$classes.' name="'.$this->getFieldName().'" id="REX_MEDIA_'.$i.'" readonly="readonly" value="'.htmlspecialchars(stripslashes($this->getValue())) . '" />
		        </p>
		        <p class="rex-widget-icons rex-widget-1col">
		          <span class="rex-widget-column rex-widget-column-first">
		            <a href="#" class="rex-icon-file-open" onclick="openREXMedia('.$i.',\'\');return false;" title="Medium auswählen"></a>
		            <a href="#" class="rex-icon-file-add" onclick="addREXMedia('.$i.');return false;" title="Neues Medium hinzufügen"></a>
		            <a href="#" class="rex-icon-file-delete" onclick="deleteREXMedia('.$i.');return false;" title="Ausgewähltes Medium löschen"></a>
		            <a href="#" class="rex-icon-file-view" onclick="viewREXMedia('.$i.');return false;" title="Medium auswählen"></a>
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
		if ($this->getElement(4) != "no_db") $this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();
	}
	
	function getDescription()
	{
		return "be_mediapool -> Beispiel: be_mediapool|label|Bezeichnung|defaultwert|no_db";
	}
	
	function getDefinitions()
	{
		return array(
						'type' => 'value',
						'name' => 'be_mediapool',
						'values' => array(
             	array( 'type' => 'name',   'label' => 'Name' ),
              array( 'type' => 'text',    'label' => 'Bezeichnung'),
              array( 'type' => 'text', 		'label' => 'Defaultwert'),
						),
						'description' => 'Mediafeld, welches eine Datei aus dem Medienpool holt',
						'dbtype' => 'text'
			);
	}
	
	
}

?>