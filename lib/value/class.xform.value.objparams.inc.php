<?php

class rex_xform_value_objparams extends rex_xform_value_abstract
{

	function init()
	{
    if (trim($this->getElement(3)) != 'runtime')
    {
      $vals = explode("#",trim($this->getElement(2)));

      if (count($vals) > 1)
      {
        $this->params[trim($this->getElement(1))] = array();
        foreach($vals as $val)
        {
          $this->params[trim($this->getElement(1))][] = $val;
        }
      }
      else
      {
        $this->params[trim($this->getElement(1))] = trim($this->getElement(2));
      }
    }
	}

	
	function enterObject()
	{
    if (trim($this->getElement(3)) == 'runtime')
    {
      $vals = explode("#",trim($this->getElement(2)));
      
      if (count($vals) > 1)
      {
        $this->params[trim($this->getElement(1))] = array();
        foreach($vals as $val)
        {
          $this->params[trim($this->getElement(1))][] = $val;
        }
      }
      else
      {
        $this->params[trim($this->getElement(1))] = trim($this->getElement(2));
      }
    }
	}
	
	
	function getDescription()
	{
		return 'objparams -> Beispiel: objparams|key|newvalue|runtime';
	}

	function getDefinitions()
	{
		return array(
						'type' => 'value',
						'name' => 'objparams',
						'values' => array(
							array( 'type' => 'name',	'value' => 'Key' ),
							array( 'type' => 'text',	'label' => 'neuer Wert'),
							array( 'type' => 'text',	'label' => 'Zur Laufzeit (runtime) oder initial (bleibt leer) '),
						),
						'description' => 'Objektparameter überschreiben',
						'dbtype' => 'text'
					);
	}

}

?>