<?php

class rex_xform_value_be_link extends rex_xform_value_abstract
{

  function enterObject()
  { 
    global $REX;
    
    if(!isset($REX["xform_classes_be_link"]))
      $REX["xform_classes_be_link"] = 0;
    
    $REX["xform_classes_be_link"]++;
    
    $i = $REX["xform_classes_be_link"];
    
    if ($this->getValue() == "" && !$this->params["send"])
      $this->setValue($this->getElement(3));


		$class = $this->getHTMLClass();
		$classes = $class;
		if (isset($this->params["warning"][$this->getId()]))
		{
			$classes .= ' '.$this->params["warning"][$this->getId()];
		}
		
		$classes = (trim($classes) != '') ? ' class="'.trim($classes).'"' : '';
		

    $linkname = "";
    if($this->getValue() != "" && $a = OOArticle::getArticleById($this->getValue()))
      $linkname = $a->getName();

		
		
    $before = '';
    $after = '';    
    $label = ($this->getElement(2) != '') ? '<label'.$classes.' for="' . $this->getFieldId() . '">' . rex_i18n::translate($this->getElement(2)) . '</label>' : '';	
		$field = '
			   <div class="rex-widget">
		      <div class="rex-widget-link">
            <p class="rex-widget-field">
              <input type="hidden" name="'.$this->getFieldName().'" id="LINK_'.$i.'" value="'.$this->getValue().'" />
              <input'.$classes.'  type="text" name="LINK_'.$i.'_NAME" value="'.htmlspecialchars($linkname).'" id="LINK_'.$i.'_NAME" readonly="readonly" />
            </p>
      
             <p class="rex-widget-icons rex-widget-1col">
              <span class="rex-widget-column rex-widget-column-first">
                <a href="#" class="rex-icon-file-open" onclick="openLinkMap(\'LINK_'.$i.'\', \'&clang=0&category_id=1\');return false;" title="Link auswählen" tabindex="21"></a>
                <a href="#" class="rex-icon-file-delete" onclick="deleteREXLink('.$i.');return false;" title="Ausgewählten Link löschen" tabindex="22"></a>
              </span>
            </p>
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
    return "be_link -> Beispiel: be_link|label|Bezeichnung|defaultwert|no_db";
  }
  
  
  function getDefinitions()
  {
    return array(
            'type' => 'value',
            'name' => 'be_link',
            'values' => array(
              array( 'type' => 'name',   'label' => 'Name' ),
              array( 'type' => 'text',   'label' => 'Bezeichnung'),
            ),
            'description' => 'Hiermit kann man einen Link zu einem REDAXO Artikel setzen.',
            'dbtype' => 'text'
      );
  }

  
  
}

?>