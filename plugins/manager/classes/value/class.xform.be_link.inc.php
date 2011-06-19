<?php

class rex_xform_be_link extends rex_xform_abstract
{

  function enterObject(&$email_elements,&$sql_elements,&$warning,&$form_output,$send = 0)
  { 
    global $REX;
    
    if(!isset($REX["xform_classes_be_link"]))
      $REX["xform_classes_be_link"] = 0;
    
    $REX["xform_classes_be_link"]++;
    
    $i = $REX["xform_classes_be_link"];
    
    if ($this->value == "" && !$send)
      if (isset($this->elements[3])) 
        $this->value = $this->elements[3];

    $wc = "";
    if (isset($warning[$this->getId()])) 
      $wc = $warning[$this->getId()];

    $linkname = "";
    if($this->getValue() != "" && $a = OOArticle::getArticleById($this->getValue()))
      $linkname = $a->getName();
      
      $form_output[] = '
      
    <div class="xform-element formbe_mediapool formlabel-'.$this->getName().'">
        <label class="text ' . $wc . '" for="el_' . $this->getId() . '" >' . $this->elements[2] . '</label>
    <div class="rex-widget">
    <div class="rex-widget-link">
      <p class="rex-widget-field">
        <input type="hidden" name="FORM['.$this->params["form_name"].'][el_'.$this->id.']" id="LINK_'.$i.'" value="'.$this->getValue().'" />
        <input type="text" size="30" name="LINK_'.$i.'_NAME" value="'.htmlspecialchars($linkname).'" id="LINK_'.$i.'_NAME" readonly="readonly" />
      </p>

       <p class="rex-widget-icons rex-widget-1col">
        <span class="rex-widget-column rex-widget-column-first">
          <a href="#" class="rex-icon-file-open" onclick="openLinkMap(\'LINK_'.$i.'\', \'&clang=0&category_id=1\');return false;" title="Link auswŠhlen" tabindex="21"></a>
          <a href="#" class="rex-icon-file-delete" onclick="deleteREXLink('.$i.');return false;" title="AusgewŠhlten Link lšschen" tabindex="22"></a>
        </span>
      </p>
    </div>
    </div>
    <div class="rex-clearer"></div>
    </div>
  ';    
    
    $email_elements[$this->elements[1]] = stripslashes($this->value);

    if (!isset($this->elements[4]) || $this->elements[4] != "no_db") 
      $sql_elements[$this->elements[1]] = $this->value;
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