<?php

class rex_xform_value_fulltext extends rex_xform_value_abstract
{

  function enterObject()
  {
  }

  function getDescription()
  {
    return "index -> Beispiel: fulltext|label|label1,label2,label3|[no_db]|[func/md5/sha]";
  }

  function preValidateAction()
  {
     
    if($this->params["send"] == 1)
    {
      $index_labels = explode(",",$this->getElement(2));

      $this->setValue("");
       
      foreach($this->obj as $o)
      {
        if(in_array($o->getName(),$index_labels)) $this->setValue($this->getValue().$o->getvalue());
      }
       
      $fnc = trim($this->getElement(4));
      if(function_exists($fnc))
      {
        $this->setValue(call_user_func($fnc, $this->getValue()));
      }
       
      $this->element_values["email"][$this->getName()] = $this->getValue();
      if ($this->getElement(3) != "no_db") $this->element_values["sql"][$this->getName()] = $this->getValue();

    }
  }

  function getDefinitions()
  {
    return array(
            'type' => 'value',
            'name' => 'fulltext',
            'values' => array(
    array( 'type' => 'name',   'label' => 'Feld' ),
    array( 'type' => 'names',  'label' => 'Names, kommasepariert'),
    array( 'type' => 'no_db',   'label' => 'Datenbank',  'default' => 1),
    array( 'type' => 'select',  'label' => 'Opt. Codierfunktion', 'default' => '0', 'definition' => 'Keine Funktion=;md5=md5;sha=sha' ),
    ),
            'description' => 'Erstellt einen Volltext über Felder/Labels, die man selbst festlegen kann.',
            'dbtype' => 'text'
            );

  }
}

?>